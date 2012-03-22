<?php
include('ast.php');
include('ranrecbuilder.php');

define("HEIGHT", 500);
define("WIDTH", 500);

function bSin($builder) {
	$s = $builder->build(1);
	return new Func1Arg(function ($x) {
		return sin(pi()*$x);
	}, $s[0], 'sin');
}
function bCos($builder) {
	$s = $builder->build(1);
	return new Func1Arg(function ($x) {
		return cos(pi()*$x);
	}, $s[0], "cos");
}
function bAvg($builder) {
	$s = $builder->build(2);
	return new Func2Arg(function ($x, $y) {
		return ($x + $y)/2;
	}, $s[0], $s[1], "avg");
}
function bProd($builder) {
	$s = $builder->build(2);
	return new Func2Arg(function ($x, $y) {
		return $x * $y;
	}, $s[0], $s[1], "prod");
}
function varx($builder) {
	return new Variable('x');
}
function vary($builder) {
	return new Variable('y');
}

$builder = new RandRecBuilder();
$builder->addBuilder('bSin', new DifVal(200, -100/8));
$builder->addBuilder('bCos', new DifVal(200, -100/8));
$builder->addBuilder('bAvg', new DifVal(200, -100/8));
$builder->addBuilder('bProd', new DifVal(200, -100/8));
$builder->addBuilder('varx', new DifVal(0, 100/8));
$builder->addBuilder('vary', new DifVal(0, 100/8));

$cExpr = $builder->build(3);

echo $cExpr[0] . "\n";
echo $cExpr[1] . "\n";
echo $cExpr[2] . "\n";

$img = imagecreatetruecolor(WIDTH, HEIGHT);

for($x = 0; $x < WIDTH; ++$x) {
	for($y = 0; $y < HEIGHT; ++$y) {
		$varBinds = array(	'x' => $x/WIDTH,
							'y' => $y/HEIGHT);
		$red = ($cExpr[0]->evaluate($varBinds) + 1) * 128;
		$green = ($cExpr[1]->evaluate($varBinds) + 1) * 128;
		$blue = ($cExpr[2]->evaluate($varBinds) + 1) * 128;
		imagesetpixel($img, $x, $y, imagecolorallocate($img, $red, $green, $blue));
	}
}

imagepng($img, 'rand.png');
	
