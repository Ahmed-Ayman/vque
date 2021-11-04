<?php

namespace Drupal\vf_reservation\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\user\Entity\User;
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
    return new JsonResponse(strlen($problem) > 0);
  }

  public function suggestionsList($problem){
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
      '#theme' => 'suggestions',
      '#user_mobile' => $data,

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

}
