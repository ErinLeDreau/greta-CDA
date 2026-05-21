<?php

namespace App\Repositories;

use App\Core\Database\Database;
use App\Models\Reservation;
use App\Models\Enums\ReservationStatusEnum;
use DateTime;

class ReservationRepository
{

    private Database $database;
    private UserRepository $userRepository;
    private MaterialRepository $materialRepository;

    /**
     * @param Database $database
     * @param UserRepository $userRepository
     * @param MaterialRepository $materialRepository
     */
    public function __construct(
        Database $database,
        UserRepository $userRepository,
        MaterialRepository $materialRepository
    ) 
    {
        $this->database = $database;
        $this->userRepository = $userRepository;
        $this->materialRepository = $materialRepository;
    }

    /**
     * @param int $id
     * @return Reservation|null
     */
    public function findById(int $id): ?Reservation
    {
        $data = $this->database->builder()
            ->table('reservations')
            ->where('id', '=', $id)
            ->first();

        return $data ? $this->hydrate($data) : null;
    }

    /**
     * @param int $userId
     * @return array
     */
    public function findByUser(int $userId): array
    {
        $rows = $this->database->builder()
            ->table('reservations')
            ->where('user_id', '=', $userId)
            ->get();

        return array_map(fn($reservationData) => $this->hydrate($reservationData), $rows);
    }

    /**
     * @param int $materialId
     * @return array
     */
    public function findByMaterial(int $materialId): array
    {
        $rows = $this->database->builder()
            ->table('reservations')
            ->where('material_id', '=', $materialId)
            ->get();

        return array_map(fn($reservationData) => $this->hydrate($reservationData), $rows);
    }

    /**
     * @param Reservation $reservation
     * @return Reservation
     */
    public function create(Reservation $reservation): Reservation
    {
        $id = $this->database->builder()
            ->table('reservations')
            ->insert([
                'material_id' => $reservation->getMaterial()->getId(),
                'user_id' => $reservation->getUser()->getId(),
                'start_date' => $reservation->getStartDate()->format('Y-m-d H:i:s'),
                'end_date' => $reservation->getEndDate()->format('Y-m-d H:i:s'),
                'status' => $reservation->getStatus()->value,
                'created_at' => $reservation->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $reservation->getUpdatedAt()->format('Y-m-d H:i:s')
            ]);
        
        $reservation->setId($id);
        return $reservation;
    }

    /**
     * @param Reservation $reservation
     * @return int
     */
    public function update(Reservation $reservation): int
    {
        return $this->database->builder()
            ->table('reservations')
            ->where('id', '=', $reservation->getId())
            ->update([
                'material_id' => $reservation->getMaterial()->getId(),
                'user_id' => $reservation->getUser()->getId(),
                'start_date' => $reservation->getStartDate()->format('Y-m-d H:i:s'),
                'end_date' => $reservation->getEndDate()->format('Y-m-d H:i:s'),
                'status' => $reservation->getStatus()->value,
                'created_at' => $reservation->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $reservation->getUpdatedAt()->format('Y-m-d H:i:s')
            ]);
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        return $this->database->builder()
            ->table('reservations')
            ->where('id', '=', $id)
            ->delete();
    }

    /**
     * @param array $data
     * @return Reservation
     */
    private function hydrate(array $data): Reservation
    {
        $user = $this->userRepository
            ->findById((int) $data['user_id']);

        $material = $this->materialRepository
            ->findById((int) $data['material_id']);

        return new Reservation(
            (int) $data['id'],
            $user,
            $material,
            new DateTime($data['start_date']),
            new DateTime($data['end_date']),
            ReservationStatusEnum::from($data['status']),
            new DateTime($data['created_at']),
            new DateTime($data['updated_at'])
        );
    }
}