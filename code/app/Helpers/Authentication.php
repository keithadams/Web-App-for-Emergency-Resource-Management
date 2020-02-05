<?php
namespace App\Helpers;
use Respect\Validation\Validator as v;

class Authentication
{
	public static function check($container)
	{
		$fields = $container->request->getParsedBody();

		$username = $fields['username'];
		$password = $fields['password'];

		$usernameValidator = v::alnum('-_.')->noWhitespace()->length(6, 50);
		$passwordValidator = V::alnum('-_.!@$%#')->length(8, 50);

		if (!$usernameValidator->validate($username) || !$passwordValidator->validate($password))
		{
			// Invalid Username or Password
			$segment = $container->session->getSegment('login');
			$segment->setFlash('login_username', $username);
			$segment->setFlash('login_error', 'Username and/or Password do not meet minimum requirements.');
			$segment->setFlash('login_error_type', 'danger');
			return false;
		}

		$result = self::prep($container);



        /*
        $db = $container->db;

		$query = 'SELECT * FROM users WHERE username = :username';
		$preparedStatement = $db->prepare($query);
		$preparedStatement->bindValue(':username', $username);
		$preparedStatement->execute();

		$user = $preparedStatement->fetchObject();
        */
        $user = Queries::getUserByUsername($username);

		if (!empty($user))
		{
			if (password_verify($password, $user->password))
			{
				// Over time as the encryption needs change, an occasional rehash might be required:
				self::checkIfNeedsRehash($container, $user->user_id, $password, $user->password);

				// Update the session to indicate we're now authenticated:
				$segment = $container->session->getSegment('authenticated');
				$segment->set('isAuthenticated', true);

				// Add the user's full info to the session:
				$userModel = new \App\Models\User($container);
				$userInfo = $userModel->getUserById($user->user_id);
				$segment = $container->session->getSegment('user');
				unset($userInfo->password);
				$segment->set('info', $userInfo);

				// Now that's all taken care of, we've successfully authenticated!
				return true;
			}
		}

		$container->logger->info("Invalid authentication attempt for: " . $username);

		// Could't find Username and/or Password Combination
		$segment = $container->session->getSegment('login');
		$segment->setFlash('login_username', $username);
		$segment->setFlash('login_error', 'Username and/or Password was invalid.');
		$segment->setFlash('login_error_type', 'danger');
		return false;
	}

	public static function prep($container)
	{
		$db = $container->db;

		$query = 'ALTER TABLE users CHANGE password password VARCHAR(255)';
		$preparedStatement = $db->prepare($query);
		$success = $preparedStatement->execute();

		if ($success)
		{
			$defaultPassword = 'password';
			$query = 'SELECT user_id, password FROM users WHERE password = :password';
			$preparedStatement = $db->prepare($query);
			$preparedStatement->bindValue(':password', $defaultPassword);
			$success = $preparedStatement->execute();

			$usersWithDefaultPasswords = $preparedStatement->fetchAll();

			foreach($usersWithDefaultPasswords as $usersWithDefaultPassword)
			{
				$query = 'UPDATE users SET password = :password WHERE user_id = :user_id';
				$preparedStatement = $db->prepare($query);
				$preparedStatement->bindValue(':user_id', $usersWithDefaultPassword->user_id);

				$password = password_hash($usersWithDefaultPassword->password, PASSWORD_DEFAULT);
				$preparedStatement->bindValue(':password', $password);
				$success = $preparedStatement->execute();
			}

			return $success;
		}

		return false;
	}

	public static function checkIfNeedsRehash($container, $user_id, $unhashedPassword, $currentPasswordHash)
	{
		if (password_needs_rehash($currentPasswordHash, PASSWORD_DEFAULT))
		{
			$db = $container->db;

			$newPasswordHash = password_hash($unhashedPassword, PASSWORD_DEFAULT);

			$query = 'UPDATE users SET password = :password WHERE user_id = :user_id';
			$preparedStatement = $db->prepare($query);
			$preparedStatement->bindValue(':user_id', $user_id);

			$preparedStatement->bindValue(':password', $newPasswordHash);
			$success = $preparedStatement->execute();

			return $success;
		}

		return false;
	}
}
