<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

class Robot
{

}


class MyHydra1 extends Robot
{

}

class MyHydra2 extends Robot
{

}


class Factory
{
    protected $types = [];

    public function __call($method, $args)
    {
        $key = strtolower(substr($method, 6));
        switch (substr($method, 0, 6)) {
            case 'create' :
                if (isset($this->types[$key])) {
                    $objects = [];
                    for($i = 0; $i < $args[0]; ++$i) {
                        $obj = clone $this->types[$key];
                        if ($obj instanceof Robot) {
                            $objects[] = clone $this->types[$key];
                        } else {
                            foreach($obj->getRobots() as $r) {
                                $objects[] = $r;
                            }
                        }
                    }
                    return $objects;
                }
            break;
        }
    }

    public function addType($object)
    {
        $this->types[strtolower(get_class($object))] = $object;
    }
}

class UnionRobot
{
    public $robots;

    public function addRobot($robot)
    {
        if (is_array($robot)) {
            foreach($robot as $r) {
                $this->robots[] = $r;
            }
        } else {
            $this->robots[] = $robot;
        }
    }

    public function getRobots()
    {
        return $this->robots;
    }
}


$factory = new Factory();

$factory->addType(new MyHydra1);
$factory->addType(new MyHydra2);

var_dump($factory->createMyHydra1(5));
var_dump($factory->createMyHydra2(2));

$unionRobot = new UnionRobot();
$unionRobot->addRobot(new MyHydra2());
$unionRobot->addRobot($factory->createMyHydra2(2));
$factory->addType($unionRobot);

var_dump($factory->createUnionRobot(1));

$reset = $factory->createUnionRobot(1);

