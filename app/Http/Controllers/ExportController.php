<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;
use Box\Spout\Common\Type;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Storage;
class ExportController extends Controller
{
    public function exportExcel()
    {
        // Get the users data from the database
        $users = DB::table('users')->select('users.first_name', 'users.last_name', 'users.branch_id', 'users.phone_number', 'users.email')->get();

        // Create a new Excel writer
        $writer = WriterEntityFactory::createXLSXWriter();

        // Set the file path and name to save the file
        $filePath = 'D:/users.xlsx';
        $writer->openToFile($filePath);

        // Write the header row
        $headerRow = WriterEntityFactory::createRowFromArray([
            'first_name', 'last_name', 'branch_id', 'phone_number', 'email'
        ]);
        $writer->addRow($headerRow);

        // Write the data rows
        foreach ($users as $user) {
            $row = WriterEntityFactory::createRowFromArray([
                $user->first_name,
                $user->last_name,
                $user->branch_id,
                $user->phone_number,
                $user->email,
            ]);
            $writer->addRow($row);
        }

        // Close the writer to save the file
        $writer->close();

        // Return a response to indicate that the file has been saved
        return response()->download($filePath, 'users.xlsx')->deleteFileAfterSend(true);

    }

}
