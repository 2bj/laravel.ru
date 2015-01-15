<?php namespace LaravelRU\User\Commands;

use Role;
use User;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class UserCommand extends Command {

	/**
	 * The console command name
	 *
	 * @var string
	 */
	protected $name = 'su:create_user';

	/**
	 * The console command description
	 *
	 * @var string
	 */
	protected $description = 'Creating user or admin.';

	/**
	 * Execute the console command
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$name = $this->argument('username');
		$password = $this->argument('password');
		$email = $this->argument('email');
		$isAdmin = $this->option('admin');

		/** @var User $user */
		$user = User::create([
			'name' => $name,
			'password' => $password,
			'email' => $email
		]);

		$user->is_confirmed = 1;
		$user->save();

		if($isAdmin){
			/** @var Role $role */
			$role = Role::whereName("administrator")->first();
			$role->users()->save($user);
		}

		$this->info("User \"$name\" created.");

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['username', InputArgument::REQUIRED, 'User Name.'],
			['password', InputArgument::REQUIRED, 'User Password.'],
			['email', InputArgument::REQUIRED, 'User Email.'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			['admin', 'a', InputOption::VALUE_NONE, 'Set admin permissions', null],
		];
	}

}
