<?php

class Robot
{
    protected $speed = 10;
    protected $weight = 10;

    protected function getSpeed() { return $this->speed; }
    protected function getWeight() { return $this->weight; }
}


class MyHydra1 extends Robot
{
    protected $speed = 11;
    protected $weight = 99;
}

class MyHydra2 extends Robot
{
    protected $speed = 12;
    protected $weight = 1;
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

class UnionRobot extends Robot
{
    protected $robots;

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
        return min(array_map(function($v) { return $v->getSpeed(); }, $this->robots));
    }

    public function getWeight()
    {
        return array_sum(array_map(function($v) { return $v->getWeight(); }, $this->robots));
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
