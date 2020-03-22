<?php
/**
 * Created by PhpStorm.
 * User: yuliang
 * Date: 2019/9/20
 * Time: 上午10:21
 */

class Group
{
    public function doSomething()
    {
        echo __CLASS__.":".'hello', '|';
    }
}

class Department
{
    private $group;
    public function __construct(Group $group)
    {
        $this->group = $group;
    }
    public function doSomething()
    {
        $this->group->doSomething();
        echo __CLASS__.":".'hello', '|';
    }
}

class Company
{
    private $department;
    public function __construct(Department $department)
    {
        $this->department = $department;
    }
    public function doSomething()
    {
        $this->department->doSomething();
        echo __CLASS__.":".'hello', '|';
    }
}