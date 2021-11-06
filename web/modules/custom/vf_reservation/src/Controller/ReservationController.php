<?php

namespace Drupal\vf_reservation\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    $categories = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->loadTree('categories');
    return [
      '#theme' => 'reservation',
      '#user_mobile' => $this->userInfo->field_mobile->value,
      '#categories' => $categories,

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
    $tid = $node->get('field_category')->target_id;
    if($tid){
      $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($tid);
    }

      $data[]=[
        'nid' => $node->id(),
        'title' => $node->get('title')->value,
        'category' => $term?$term->getName():'',
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
    $category = $request->get('category');
  }

  public function searchOnSuggestions($word){
    $arr_words = explode(' ', trim($word));
    $query = \Drupal::entityQuery('node');
    $query->condition('type', "problems");
    $group = $query->orConditionGroup();
    foreach ($arr_words as $arr_word){
      $group->condition('title', "%$arr_word%", 'like');
      $group->condition('body', "%$arr_word%", 'like');
    }
    $query->condition($group);
    return $query->execute();
  }

}
