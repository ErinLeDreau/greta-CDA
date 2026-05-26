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
    protected string $table = 'reservations';

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
     * @param Reservation $reservation
     * @return Reservation
     */
    public function update(Reservation $reservation): Reservation
    {
        $this->database->builder()
            ->table($this->table)
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
        return $reservation;
    }

    /**
     * @param int $id
     * @return int
     */
    public function delete(int $id): int
    {
        return $this->database->builder()
            ->table($this->table)
            ->where('id', '=', $id)
            ->delete();
    }

    /**
     * @param int $userId
     * @return int
     */
    public function deleteByUserId(int $userId): int
    {
        return $this->database->builder()
            ->table($this->table)
            ->where('user_id', '=', $userId)
            ->delete();
    }

    /**
     * @param int $materialId
     * @return int
     */
    public function deleteByMaterialId(int $materialId): int
    {
        return $this->database->builder()
            ->table($this->table)
            ->where('material_id', '=', $materialId)
            ->delete();
    }

    /**
     * @param int $materialId
     * @param DateTime $start
     * @param DateTime $end
     * @param int|null $reservationId
     * @return array
     */
    public function findActiveOverlapping(
        int $materialId,
        DateTime $start,
        DateTime $end,
        ?int $reservationId
    ): array
    {
        $rows = $this->database->builder()
            ->table('reservations')
            ->where('material_id', '=', $materialId)
            ->where('status', '=', 'ACTIVE')
            ->where('start_date', '<', $end->format('Y-m-d H:i:s'))
            ->where('end_date', '>', $start->format('Y-m-d H:i:s'))
            ;
        
        if($reservationId){
            $rows = $rows->where('id','!=', $reservationId);
        }
        
        $rows = $rows->get();

        return array_map(
            fn(array $row) => $this->hydrate($row),
            $rows
        );
    }

    /**
     * @return Reservation[]
     */
    public function findAll(): array
    {
        $rows = $this->database->builder()
            ->table($this->table)
            ->orderBy('start_date','ASC')
            ->get();

        return array_map(
            fn(array $row) => $this->hydrate($row),
            $rows
        );
    }
    /**
     * @return Reservation[]
     */
    public function findAllFuture(): array
    {
        $rows = $this->database->builder()
            ->table($this->table)
            ->where('end_date', '>', (new DateTime())->format('Y-m-d H:i:s'))
            ->orderBy('start_date','ASC')
            ->get();

        return array_map(
            fn(array $row) => $this->hydrate($row),
            $rows
        );
    }

    /**
     * @param int $id
     * @return Reservation|null
     */
    public function findById(int $id): ?Reservation
    {
        $data = $this->database->builder()
            ->table($this->table)
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
            ->table($this->table)
            ->where('user_id', '=', $userId)
            ->orderBy('start_date','ASC')
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
            ->table($this->table)
            ->where('material_id', '=', $materialId)
            ->orderBy('start_date','ASC')
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
            ->table($this->table)
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