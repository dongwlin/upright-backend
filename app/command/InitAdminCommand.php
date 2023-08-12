<?php
declare (strict_types = 1);

namespace app\command;

use app\admin\model\AdminModel;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\db\exception\DbException;

class InitAdminCommand extends Command
{
    protected function configure()
    {
        // 指令配置
        $this->setName('initAdmin')
            ->addOption('pwd', 'p', Option::VALUE_OPTIONAL, 'password')
            ->setDescription('init admin');
    }

    protected function execute(Input $input, Output $output)
    {
        if ($input->hasOption('pwd'))
        {
            $password = $input->getOption('pwd');
        }
        else
        {
            $tmp = sha1(strval(time()));
            $password = substr($tmp, 0, 6);
        }
        try {
            $admin = AdminModel::where('username', '=', 'admin')->findOrEmpty();
            if ($admin->isEmpty())
            {
                AdminModel::create([
                    'username' => 'admin',
                    'password' => password_hash(sha1($password), PASSWORD_BCRYPT),
                    'is_super_admin' => true
                ]);
            }
            else
            {
                $admin->save([
                    'password' => password_hash(sha1($password), PASSWORD_BCRYPT)
                ]);
            }
        }
        catch (DbException $exception)
        {
            $output->writeln('Error: ' . $exception->getMessage());
            return;
        }
        $output->writeln('init admin successful');
        $output->writeln('username: admin');
        $output->writeln('password: ' . $password);
    }
}
