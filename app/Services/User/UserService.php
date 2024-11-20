<?php

namespace App\Services\User;

use App\Models\Auth\Jwt;
use App\Models\ProfileConfirm;
use App\Models\User;
use App\Repositories\User\IProfileConfirmationRepository;
use App\Repositories\User\IUserRepository;
use App\Services\BruteForce\BruteForceProtector;
use App\Services\Mail\IMailService;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class UserService implements IUserService
{
    public function __construct(
        private IUserRepository                $userRepository,
        private IProfileConfirmationRepository $profileConfirmation,
        private IMailService                   $mailService,
        private Jwt            $jwt
    ){}

    public function register(array $data): JsonResponse
    {
        $registeredUser =  $this->userRepository->create($data);

        $profileConfirm = $this->profileConfirmation->create([
            'user_id' => $registeredUser->id,
            'email' => $registeredUser->email,
            'token' => $this->generateToken(),
            'status' => ProfileConfirm::TOKEN_STATUS_VALID
        ]);

        $mailTemplate = view('mail.index', ['token' => $profileConfirm->token])->render();

        $confirmationEmailData = [
            'email' => $data['email'],
            'body' => $mailTemplate,
        ];

        $this->mailService->send($confirmationEmailData, $profileConfirm);

        return response()->json([
            'status' => 'OK',
            'data' => [
                'message' => 'Successfully Profile Registration',
            ]
        ]);
    }

    public function confirm(string $token): JsonResponse
    {
        $profileConfirm = $this->getProfileConfirmByToken($token);
        if (!empty($profileConfirm)) {
            $user = $this->getUserById($profileConfirm->user_id);
            $this->userRepository->update($user, ['is_confirmed' => true]);
            $this->profileConfirmation->update($profileConfirm, ['status' => ProfileConfirm::TOKEN_STATUS_EXPIRED]);

            return response()->json([
                'status' => 'OK',
                'data' => [
                    'message' => 'Successfully Profile Confirmation',
                ]
            ]);
        } else {
            return response()->json([
                'status' => 'ERROR',
                'message' => 'Confirmation Token Is Invalid',
            ]);
        }
    }

    public function sendConfirmationMessage(string $email): JsonResponse
    {
        $user = $this->getUserByEmail($email);

        if (empty($user)) {
            throw new HttpResponseException(response()->json([
                'status' => 'INVALID_DATA',
                'errors' => [
                    'email' => 'invalid email',
                ]
            ], 200));
        }

        $this->invalidatePreviousConfirmAttempts($user->email);

        $profileConfirm = $this->profileConfirmation->create([
            'user_id' => $user->id,
            'email' => $user->email,
            'token' => $this->generateToken(),
            'status' => ProfileConfirm::TOKEN_STATUS_VALID
        ]);

        $mailTemplate = view('mail.index', ['token' => $profileConfirm->token])->render();

        $confirmationEmailData = [
            'email' => $user->email,
            'body' => $mailTemplate,
        ];

        $this->mailService->send($confirmationEmailData, $profileConfirm);

        return response()->json([
            'status' => 'OK',
            'data' => [
                'message' => 'Confirmation Message Sent',
            ]
        ]);

    }

    public function authorize(array $credentials): JsonResponse
    {
        $authAttemptKey = Request::ip() . '|' . $credentials['email'];

        if (!BruteForceProtector::check($authAttemptKey)) {
            throw new HttpResponseException(response()->json([
                'status' => 'INVALID_DATA',
                'errors' => [
                    'email' => 'too many attempts try letter',
                    'password' => 'too many attempts try letter'
                ]
            ], 200));
        }

        if (Auth::validate($credentials)) {
            $user = $this->getUserByEmail($credentials['email']);
            $this->userRepository->update($user, ['last_login_at' => now()]);

                return response()->json([
                    'status' => 'OK',
                    'data' => [
                        'jwt' => $this->jwt->fromUser($user),
                    ]
                ]);

        } else {
            throw new HttpResponseException(response()->json([
                'status' => 'INVALID_DATA',
                'errors' => [
                    'email' => 'invalid credentials',
                    'password' => 'invalid credentials'
                ]
            ], 200));
        }
    }

    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }

    public function getUserById(int $id): ?User
    {
        return User::where('id', $id)->first();
    }

    public function getProfileConfirmByToken(string $token): ?ProfileConfirm
    {
        return ProfileConfirm::where('token', $token)->where('status', ProfileConfirm::TOKEN_STATUS_VALID)->first();
    }

    private function generateToken(): string
    {
        $token = Str::random(40);

        if (empty(ProfileConfirm::where('token', $token)->first())) {
            return $token;
        }

        return $this->generateToken();
    }

    private function invalidatePreviousConfirmAttempts(string $email): void
    {
        $validProfileConfirmation =  ProfileConfirm::where('email', $email)->where('status', ProfileConfirm::TOKEN_STATUS_VALID)->first();
        if (!empty($validProfileConfirmation)) {
            $this->profileConfirmation->update($validProfileConfirmation, ['status' => ProfileConfirm::TOKEN_STATUS_EXPIRED]);
        }
    }
}
