<?php
require "./vendor/autoload.php";
$redis=new Predis\Client();
$cachedEntry=$redis->get('actor');
$t0=0;
$t1=0;
if($cachedEntry)
{
    echo "Data is displayed from Cache<br>";
    $t0=microtime(true)*1000;
    echo $cachedEntry;
    $t1=microtime(true)*1000;
    echo 'Time taken: '.round($t1-$t0,4);
    exit();
}
else
{
    include('config.php');
    $t0=microtime(true)*1000;
    $sql="select firstname,lastname from actor;";
    $result=$conn->query($sql);
    echo "From Database <br>";
    $temp='';
    while($row=$result->fetch(PDO::FETCH_ASSOC))
    {
        echo $row['firstname'].'<br>';
        echo $row['lastname'].'<br>';
        $temp .=$row['firstname']. ' ' .$row['lastname'].'<br>';
    }
    
    $t1=microtime(true)*1000;
    echo 'Time taken: '.round($t1-$t0,4);
    $redis->set('actor',$temp);
    $redis->expire('actor',10);
    exit();
}

?>
