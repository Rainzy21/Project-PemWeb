<?php

namespace App\Controllers\Traits;

trait ExportsUsers
{
    /**
     * Export users to CSV
     */
    protected function exportUsersToCsv($userModel, ?string $role): void
    {
        $users = ($role && $this->isValidRole($role))
            ? $userModel->getByRole($role)
            : $userModel->all();

        $filename = 'users_' . date('Y-m-d') . '.csv';
        $headers = ['ID', 'Name', 'Email', 'Phone', 'Role', 'Created At'];

        $data = array_map(fn($user) => [
            $user->id,
            $user->name,
            $user->email,
            $user->phone,
            $user->role,
            $user->created_at
        ], $users);

        $this->exportToCsv($filename, $headers, $data);
    }

    /**
     * Get new users this month count
     */
    protected function getNewUsersThisMonth($userModel): int
    {
        $result = $userModel->raw(
            "SELECT COUNT(*) as count FROM users 
             WHERE MONTH(created_at) = MONTH(CURRENT_DATE())
             AND YEAR(created_at) = YEAR(CURRENT_DATE())"
        );

        return $result[0]->count ?? 0;
    }
}