<?php
namespace Twarch\Module;

class Find extends \Twarch\Module {
  public function exec($args){
  
    $searchTerm = $args->getResidualArg(1);

    $findTweets = $this->db->prepare('
      SELECT id, created, text FROM tweets WHERE text match :searchTerm ORDER BY created ASC
    ');

    $findTweets->execute(array(
      'searchTerm' => $searchTerm
    ));

    $fields = array('Id', 'Created', 'Text');

    $rows = array();
    while ($tweet = $findTweets->fetch(\PDO::FETCH_OBJ)){
      $rows[] = array(
        $tweet->id,
        date(DATE_ISO8601, $tweet->created), 
        html_entity_decode($tweet->text)
      );
    }

    $this->setResult(
      new \Twarch\Result\Table($fields, $rows)
    );

    return true;
  }
}
