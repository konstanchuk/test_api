<?php

class Robot
{
    public $speed = 10;
    public $weight = 10;
}


class MyHydra1 extends Robot
{
    public $speed = 11;
    public $weight = 99;
}

class MyHydra2 extends Robot
{
    public $speed = 12;
    public $weight = 1;
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
                        $objects[] = clone $this->types[$key];
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

    public function getSpeed()
    {
        return min(array_map(function($v) { return $v->speed; }, $this->robots));
    }

    public function getWeight()
    {
        return array_sum(array_map(function($v) { return $v->weight; }, $this->robots));
    }
}


$factory = new Factory();

$factory->addType(new MyHydra1);
$factory->addType(new MyHydra2);

var_dump($factory->createMyHydra1(5));
var_dump($factory->createMyHydra2(2));

$unionRobot = new UnionRobot();
$unionRobot->addRobot(new MyHydra1());
$unionRobot->addRobot($factory->createMyHydra2(2));
$factory->addType($unionRobot);

var_dump($factory->createUnionRobot(1));

$res = reset($factory->createUnionRobot(1));
echo $res->getSpeed() . '<br />';
echo $res->getWeight();
