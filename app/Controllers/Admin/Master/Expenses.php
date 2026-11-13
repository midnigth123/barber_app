<?php

namespace App\Controllers\Admin\Master;

use App\Controllers\BaseController;

class Expenses extends BaseController
{
    // Tampilkan Daftar Pengeluaran & Total Pengeluaran
    public function index()
    {
        $db = \Config\Database::connect();

        $expenses = $db->table('expenses')
            ->orderBy('expense_date', 'DESC')
            ->orderBy('id', 'DESC')
            ->get()
            ->getResultArray();

        $totalExpense = $db->table('expenses')->selectSum('amount', 'total')->get()->getRow()->total ?? 0;

        $data = [
            'title'        => 'Manajemen Pengeluaran',
            'expenses'     => $expenses,
            'totalExpense' => $totalExpense
        ];

        return view('admin/products/expenses', $data);
    }

    // Proses Tambah Pengeluaran Baru
    public function store()
    {
        $db = \Config\Database::connect();

        $data = [
            'expense_date' => $this->request->getPost('expense_date'),
            'title'        => $this->request->getPost('title'),
            'category'     => $this->request->getPost('category'),
            'amount'       => str_replace(['.', ','], ['', '.'], $this->request->getPost('amount')),
            'description'  => $this->request->getPost('description'),
            'created_at'   => date('Y-m-d H:i:s')
        ];

        $db->table('expenses')->insert($data);

        return redirect()->to('/admin/products/expenses')->with('success', 'Data pengeluaran berhasil ditambahkan.');
    }

    // Proses Update / Edit Pengeluaran
    public function update($id)
    {
        $db = \Config\Database::connect();

        $data = [
            'expense_date' => $this->request->getPost('expense_date'),
            'title'        => $this->request->getPost('title'),
            'category'     => $this->request->getPost('category'),
            'amount'       => str_replace(['.', ','], ['', '.'], $this->request->getPost('amount')),
            'description'  => $this->request->getPost('description'),
            'updated_at'   => date('Y-m-d H:i:s')
        ];

        $db->table('expenses')->where('id', $id)->update($data);

        return redirect()->to('/admin/products/expenses')->with('success', 'Data pengeluaran berhasil diperbarui.');
    }

    // Proses Hapus Pengeluaran
    public function delete($id)
    {
        $db = \Config\Database::connect();
        $db->table('expenses')->where('id', $id)->delete();

        return redirect()->to('/admin/products/expenses')->with('success', 'Data pengeluaran berhasil dihapus.');
    }
}
