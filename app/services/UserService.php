<?php
require_once APPROOT . '/repositories/UserRepository.php';

class UserService
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository = null)
    {
        $this->userRepository = $userRepository ?: new UserRepository();
    }

    public function getAdmins()
    {
        return $this->userRepository->getByRole('users', 1);
    }

    public function getUserById(int $id)
    {
        return $this->userRepository->getById('view_user_with_role', $id);
    }

    public function deleteAdmin(int $id, int $loginUserId, string $loginUserEmail)
    {
        if ($loginUserEmail !== 'admin@gmail.com') {
            return ['success' => false, 'message' => "❌ Only default admin can delete admins."];
        }
        if ($id === $loginUserId) {
            return ['success' => false, 'message' => "❌ You cannot delete yourself!"];
        }
        $deleted = $this->userRepository->delete('users', $id);
        if ($deleted) {
            return ['success' => true, 'message' => "✅ Admin deleted successfully."];
        }
        return ['success' => false, 'message' => "❌ Failed to delete admin."];
    }

    public function deleteCustomer(int $id)
    {
        return $this->userRepository->delete('users', $id);
    }

    public function getCustomersWithTickets()
    {
        $users = $this->userRepository->getByRole('users', 2);
        $tickets = $this->userRepository->readAll('seats');
        $routes = $this->userRepository->readAll('route');
        $operators = $this->userRepository->readAll('operator');
        $busTypes = $this->userRepository->readAll('bus_type');

        $busTypeMap = [];
        foreach ($busTypes as $bt) {
            $busTypeMap[$bt['id']] = $bt['type_name'];
        }

        $operatorMap = [];
        foreach ($operators as $op) {
            $busTypeName = $busTypeMap[$op['bus_type_id']] ?? '';
            $op['bus_type_name'] = $busTypeName;
            $operatorMap[$op['id']] = $op;
        }

        $routeMap = [];
        foreach ($routes as $route) {
            $routeMap[$route['id']] = $route;
        }

        $ticketStats = [];
        $userTickets = [];

        foreach ($tickets as $ticket) {
            $userId = $ticket['user_id'] ?? null;
            if (!$userId) continue;

            $route = $routeMap[$ticket['route_id']] ?? null;
            if ($route) {
                $ticket['route_from'] = $route['from'];
                $ticket['route_to'] = $route['to'];
                $ticket['price'] = $route['price'];
                $ticket['departure_time'] = $route['departure_time'];

                $opId = $route['operator_id'] ?? null;
                if ($opId && isset($operatorMap[$opId])) {
                    $ticket['operator_name'] = $operatorMap[$opId]['name'] ?? '';
                    $ticket['bus_type'] = $operatorMap[$opId]['bus_type_name'] ?? '';
                } else {
                    $ticket['operator_name'] = '';
                    $ticket['bus_type'] = '';
                }
            } else {
                $ticket['route_from'] = '';
                $ticket['route_to'] = '';
                $ticket['price'] = '';
                $ticket['departure_time'] = '';
                $ticket['operator_name'] = '';
                $ticket['bus_type'] = '';
            }

            $dt = new DateTime($ticket['created_at']);
            $ticket['created_at_formatted'] = $dt->format('M j - g:i');

            if (!isset($userTickets[$userId])) {
                $userTickets[$userId] = [];
            }
            $userTickets[$userId][] = $ticket;

            if (!isset($ticketStats[$userId])) {
                $ticketStats[$userId] = [
                    'total_tickets' => 0,
                    'last_booking' => null,
                    'last_booking_raw' => null,
                ];
            }
            $ticketStats[$userId]['total_tickets']++;

            if (
                !$ticketStats[$userId]['last_booking_raw'] ||
                strtotime($ticket['created_at']) > strtotime($ticketStats[$userId]['last_booking_raw'])
            ) {
                $ticketStats[$userId]['last_booking_raw'] = $ticket['created_at'];
                $ticketStats[$userId]['last_booking'] = $ticket['created_at_formatted'];
            }
        }

        return [
            'user' => $users,
            'ticketStats' => $ticketStats,
            'userTickets' => $userTickets,
        ];
    }
}
