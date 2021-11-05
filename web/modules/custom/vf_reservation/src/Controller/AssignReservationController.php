<?php

namespace Drupal\vf_reservation\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Drupal\user\Entity\User;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class AssignReservationController.
 */
class AssignReservationController extends ControllerBase
{

  /**
   * Assignreservationtouser.
   *
   * @return string
   *   Return Hello string.
   */
  public function assignReservationToUser($rid, $uid)
  {
    $reservation = Node::load($rid);
    $userAgent = User::load($uid);

    $userScreen = Node::load($userAgent->get('field_assigned_screen')->getString());
    // update reservation status and screen
    $reservation->set('field_reservation_screen', $userScreen);
    $reservation->set('field_reservation_status', "being served");
    $reservation->save();
    // currently served customer
    $storeNode = Node::load($userAgent->get('field_user_store')->getString());
    $userQueueNumber = $reservation->get('field_reservation_queue_number')->getString();
    $storeNode->set('field_currently_served_customer', $userQueueNumber);
    $storeNode->save();
    // update screen counter
    $screenNode = Node::load($userAgent->get('field_assigned_screen')->getString());

    $screenNode->set('field_counter', $userQueueNumber)->save();

    \Drupal::messenger()->addMessage("assigned successfully!");
    $redirect = new RedirectResponse("/user");
    return $redirect->send();
  }

  public function resolve($rid)
  {
    $reservationNode = Node::load($rid);
    $reservationNode->set('field_reservation_status', 'Problem resolved');
    $reservationNode->set('field_reservation_screen', Null);
    $reservationNode->set('field_reservation_queue_number', Null);
    $reservationNode->save();

    $storeNode = Node::load($reservationNode->get("field_reservation_store")->getString());
    $originalPeopleInQueue = intval($storeNode->get('field_number_of_people_in_queue')->getString());
    $storeNode->set('field_number_of_people_in_queue', $originalPeopleInQueue - 1);
    $storeNode->set('field_currently_served_customer', 0);

    $storeNode->save();

    $userAgent = User::load(\Drupal::currentUser()->id());
    $screenNode = Node::load($userAgent->get('field_assigned_screen')->getString());
    $screenNode->set('field_counter', Null)->save();
    \Drupal::messenger()->addMessage("resolved successfully!");
    $redirect = new RedirectResponse("/user");
    return $redirect->send();
  }
}
