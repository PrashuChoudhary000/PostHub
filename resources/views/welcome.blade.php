<?php 

use App\Models\Post;
// trait Loggable 
// {
//     public function log(string $message) : void 
//     {
//         echo "[". static::class . "] ". $message;
//     }
// }

// class Order
// {
//     use Loggable;
// }

// class User 
// {
//     use Loggable;
// }

// (new order())->log('Created');
// echo "<br>";
// (new User())->log('Logged In');
// echo "<br>";



// function withdraw(int $balance, int $amount): int 
// {
//     if ($amount > $balance) {
//         throw new InvalidArgumentException('Insufficient funds'); 
//     }

//     return $balance - $amount;
// }


// try {
//     echo withdraw(500, 1000);
// } catch(InvalidArgumentException $e) {
//     echo "The error  message is : ". $e->getMessage();
// }finally{
//     echo  "It always runs";
// }



// $posts = Post::all();

// var_dump($posts[0]->title);

// dd(Post::where('published', 1)->get());