<?php

namespace Tigreboite\FunkylabBundle\Command;

//use Carbon\Carbon;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;
use Tigreboite\FunkylabBundle\Entity\User;

class UserCommand extends ContainerAwareCommand
{
    const ROLES_ADMIN = ["ROLE_USER", "ROLE_ADMIN"];
    const ROLES_APP = ["ROLE_USER", "ROLE_APP"];
    const ROLES_USER = ["ROLE_USER"];
    private $input;
    private $output;

    protected function configure()
    {
        $this
            ->setName('funkylab:user')
            ->setDescription('Manage users')
            ->addArgument('action', InputArgument::OPTIONAL, "create | delete | set-active | update-password | update-roles");
    }

    /**
     * Resetting current order of yesterday
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $action = $input->getArgument('action', false);
        if (!$action)
            return;

        switch ($action) {
            case "update-password":
                $this->changePasswordUser();
                break;
            case "update-roles":
                $this->changeRolesUser();
                break;
            case "delete":
                $this->deleteUser();
                break;
            case "set-active":
                $this->activeUser();
                break;
            case "create":
            default:
                $this->createUser();
                break;
        }
        return;
    }

    private function changePasswordUser()
    {

        $this->output->writeln("---------------------------");
        $this->output->writeln("Update Password");
        $this->output->writeln("---------------------------");
        $email = $this->askQuestion('Username');
        $manager = $this->getContainer()->get('funkylab.manager.user');
        $user = $manager->findOneBy(['username' => $email]);
        if (!$user) {
            $this->output->writeln("<error>Not found</error>");
            return;
        }

        $password = $this->askQuestion('New password');
        $encoder = $this->getContainer()->get('security.password_encoder');
        $password = $encoder->encodePassword($user, $password);
        $user->setPassword($password);
        $manager->save($user, true);
        $this->output->writeln("<comment>Password updated</comment>");
    }

    private function askQuestion($question)
    {
        $helper = $this->getHelper('question');
        $question = new Question("<info>" . $question . " ?</info> ");
        $question->setValidator(function ($value) {
            if (trim($value) == '') {
                throw new \Exception('This can\'t not be empty');
            }

            return $value;
        });
        return $helper->ask($this->input, $this->output, $question);
    }

    private function changeRolesUser()
    {

        $this->output->writeln("---------------------------");
        $this->output->writeln("Update Role");
        $this->output->writeln("---------------------------");
        $email = $this->askQuestion('Username');
        $manager = $this->getContainer()->get('funkylab.manager.user');
        $user = $manager->findOneBy(['username' => $email]);
        if (!$user) {
            $this->output->writeln("<error>Not found</error>");
            return;
        }

        $roles = $this->askRoles();
        $user->setRoles($roles);
        $manager->save($user, true);
        $this->output->writeln("<comment>Roles updated</comment>");
    }

    private function askRoles()
    {
        $helper = $this->getHelper('question');
        $question = new ChoiceQuestion(
            'Please select a role',
            array('app', 'admin', 'user'),
            0
        );
        $roles = $helper->ask($this->input, $this->output, $question);

        if ($roles == "app") {
            $roles = self::ROLES_APP;
        } else if ($roles == "admin") {
            $roles = self::ROLES_ADMIN;
        } else {
            $roles = self::ROLES_USER;
        }

        return $roles;
    }

    private function deleteUser()
    {

        $this->output->writeln("---------------------------");
        $this->output->writeln("Delete User");
        $this->output->writeln("---------------------------");
        $manager = $this->getContainer()->get('funkylab.manager.user');
        $username = $this->askQuestion('Username');
        $user = $manager->findOneBy(['username' => $username]);
        if (!$user) {
            $this->output->writeln("<error>Not found</error>");
            return;
        }
        if ($this->askConfirm("Are you sure (Y|N)")) {
            $manager->remove($user);
            $this->output->writeln("<comment>User deleted</comment>");
        } else {
            $this->output->writeln("<comment>Canceled</comment>");
        }
    }

    private function askConfirm($question)
    {
        $helper = $this->getHelper('question');
        $question = new ConfirmationQuestion(
            "<info>" . $question . " ?</info>",
            false,
            '/^(y|j)/i'
        );
        return $helper->ask($this->input, $this->output, $question);

    }

    private function activeUser()
    {
        $this->output->writeln("---------------------------");
        $this->output->writeln("Toggle Active User");
        $this->output->writeln("---------------------------");
        $email = $this->askQuestion('Username');
        $manager = $this->getContainer()->get('funkylab.manager.user');
        $user = $manager->findOneBy(['username' => $email]);
        if (!$user) {
            $this->output->writeln("<error>Not found</error>");
            return;
        }

        if ($user->getIsActive()) {
            $user->setIsActive(false);
            $this->output->writeln("<comment>User is now inactive</comment>");
        } else {
            $user->setIsActive(true);
            $this->output->writeln("<comment>User is now active</comment>");
        }
        $manager->save($user, true);
    }

    private function createUser()
    {
        $this->output->writeln("---------------------------");
        $this->output->writeln("Create user");
        $this->output->writeln("---------------------------");

        $data = [];
        $data['username'] = $this->askQuestion('Username');

        $manager = $this->getContainer()->get('funkylab.manager.user');
        $user = $manager->findOneBy(['username' => $data['username']]);
        if ($user) {
            $this->output->writeln("<error>User already exist</error>");
            return;
        }

        $data['email'] = $this->askQuestion('Email');
        $data['password'] = $this->askQuestion('Password');

        $data['roles'] = $this->askRoles();

        $encoder = $this->getContainer()->get('security.password_encoder');

        $user = new User();
        $user->setUsername($data['username']);
        $password = $encoder->encodePassword($user, $data['password']);
        $user->setPassword($password);
        $user->setRoles($data['roles']);
        $user->setEmail($data['email']);

        $manager->save($user, true);
        $this->output->writeln("<comment>New user created</comment>");
    }

}
