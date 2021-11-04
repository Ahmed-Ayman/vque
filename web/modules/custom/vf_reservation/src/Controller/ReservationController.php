<?php

namespace Drupal\vf_reservation\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Drupal\views\Views;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends ControllerBase
{

  public $userInfo;
  public function __construct()
  {
    $this->userInfo = User::load(\Drupal::currentUser()->id());
  }

  public function home()
  {
    return [
      '#theme' => 'reservation',
      '#user_mobile' => $this->userInfo->field_mobile->value,

    ];
  }
  public function checkSuggestions($problem, $mobile) {
    //check count >8
    $result = $this->searchOnSuggestions($problem);
    $temp = \Drupal::service('tempstore.private')->get('suggestions');
    $temp->set('problem', $problem);
    return new JsonResponse(count($result) > 0);
  }

  public function suggestionsList(){
    $temp = \Drupal::service('tempstore.private')->get('suggestions');
    $problem =   $temp->get('problem');
    $result = $this->searchOnSuggestions($problem);
    $nodes = Node::loadMultiple($result);
    $data = [];
    foreach ($nodes as $node){
      $data[]=[
        'nid' => $node->id(),
        'title' => $node->get('title')->value,
      ];
    }
    return [
      '#theme' => 'suggestions',
      '#suggtions' => $data,

    ];
  }
  public function storesList(Request $request){
    $mobile = $request->get('mobile');
    $problem = $request->get('problem');

    $data =[
      [
        'title'=>'mo',
        'id'=>1
      ],[
        'title'=>'mo',
        'id'=>1
      ],[
        'title'=>'mo',
        'id'=>1
      ]
    ];
    return [
      '#theme' => 'stores',
      '#user_mobile' => $data,

    ];
  }

  public function searchOnSuggestions($word){
    $query = \Drupal::entityQuery('node');
    $query->condition('type', "problems");
    $group = $query->orConditionGroup()
        ->condition('title', "%$word%", 'like')
        ->condition('body', "%$word%", 'like');
    $query->condition($group);
    return $query->execute();
  }
}
