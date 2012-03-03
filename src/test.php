<?php
include('ast.php');
include('ranrecbuilder.php');

function bSin($builder) {
	$s = $builder->build(1);
	return new Func1Arg(function ($x) {
		return sin(2*pi()*$x);
	}, $s[0], 'sin');
}
function bCos($builder) {
	$s = $builder->build(1);
	return new Func1Arg(function ($x) {
		return cos(2*pi()*$x);
	}, $s[0], "cos");
}
function bAvg($builder) {
	$s = $builder->build(2);
	return new Func2Arg(function ($x, $y) {
		return ($x + $y)/2;
	}, $s[0], $s[1], "avg");
}
function varx($builder) {
	return new Variable('x');
}
function vary($builder) {
	return new Variable('y');
}

$builder = new RandRecBuilder();
$builder->addBuilder('bSin', 100);
$builder->addBuilder('bCos', 100);
$builder->addBuilder('bAvg', 100);
$builder->addBuilder('varx', 60);
$builder->addBuilder('vary', 60);

$expr = $builder->build(1); 

$varBinds = array( 'x' => 0.5, 'y' => 0.2 );

print $expr[0] . "\n";
print $expr[0]->evaluate($varBinds) . "\n";
