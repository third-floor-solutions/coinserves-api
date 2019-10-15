<?php

namespace App\Repository;

use App\Model\Blockchain;
use App\Model\User;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class BlockchainRepository extends Repository
{
  protected $model;

  public function __construct()
  {
    $this->model = new Blockchain();
  }

  public function find($id, $keys = [])
  {
    return $this->model->findOrFail($id,$keys);
  }

  public function create($data = [])
  {
    return $this->model->create($data)->fresh();
  }

  public function update($id, $data = null)
  {
    $blog = Blog::findOrFail($id);
    $blog->update($data);
    return $blog->fresh();
  }

  public function getTransactions($wallet_address){
    $client = new Client([
      'base_uri' => 'https://blockchain.info/',
      'timeout'  => 5.0,
    ]);
    // /*sample wallet address
    // *1AJbsFZ64EpEfS5UAjAfcUG8pH8Jn3rn1F
    // *1A8JiWcwvpY7tAopUkSnGuEYHmzGYfZPiq
    // *1MDUoxL1bGvMxhuoDYx6i11ePytECAk9QK
    // *1Kr6QSydW9bFQG1mXiPNNu6WpJGmUa9i1g
    // */
    try {
        $requestBlockChain = $client->request('GET', 'rawaddr/' . $wallet_address . '?limit=0');
    } catch (RequestException $e) {
        return null;
    }
    $response = $requestBlockChain->getBody();
    $obj = json_decode($response);
    
    $blockchain = Blockchain::where('wallet_address', $wallet_address)->first();
    
    $tx = $obj->n_tx - $blockchain->initial_tx;
    $trees = floor($tx/3);

    $blockchain->cnsrv_n_tx = $obj->n_tx;
    $blockchain->trees = $trees;

    $blockchain->save();
    return $blockchain->fresh();
  }

  public function getTransactionsByUserId($user_id){
    $client = new Client([
      'base_uri' => 'https://blockchain.info/',
      'timeout'  => 5.0,
    ]);

    $blockchains = Blockchain::where('user_id', $user_id)->get();
    $tx = [];
    foreach($blockchains as $blockchain) {
      $transaction = $this->getTransactions($blockchain->wallet_address);
      if($transaction) {
        array_push($tx,$transaction);
      }
    }
    return $tx;
  }
}