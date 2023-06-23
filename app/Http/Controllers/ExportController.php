<?php

namespace App\Http\Controllers;

use App\Models\User;
use Box\Spout\Common\Helper\Escaper\XLSX;
use Box\Spout\Writer\Common\Creator\WriterEntityFactory;
use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\DB;
use PDF;
use Illuminate\Http\Request;

class ExportController extends Controller
{

    public function encryptExcel(Request $request)
    {
        // Get the users data from the database
        $trainers = DB::table('trainer_profiles')
            ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
            ->select('users.first_name', 'users.last_name','users.branch_id','users.phone_number', 'users.email', 'trainer_profiles.rating')
            ->get();
        // Get the encryption key from the input field
        $encryptionKey = $request->input('encryption_key');

        // Create a new Excel writer
        $writer = WriterEntityFactory::createXLSXWriter();

        // Set the file path and name to save the file
        $filePath = 'D:/trainers.xlsx';
        $writer->openToFile($filePath);

        // Write the header row
        $headerRow = WriterEntityFactory::createRowFromArray([
            'first_name', 'last_name', 'branch_id', 'phone_number', 'email', 'rating'
        ]);
        $writer->addRow($headerRow);


        $iv = '1234567890123456'; // يمكن تعديل القيمة

        // Write the data rows
        foreach ($trainers as $trainer) {
            // Generate a random IV for each value

            // Encode each value using a password and a random IV
            $encryptedFirstName = openssl_encrypt($trainer->first_name, 'AES-256-CBC', $encryptionKey, 0, $iv);
            $encryptedLastName = openssl_encrypt($trainer->last_name, 'AES-256-CBC', $encryptionKey, 0, $iv);
            $encryptedBranchId = openssl_encrypt($trainer->branch_id, 'AES-256-CBC', $encryptionKey, 0, $iv);
            $encryptedPhoneNumber = openssl_encrypt($trainer->phone_number, 'AES-256-CBC', $encryptionKey, 0, $iv);
            $encryptedEmail = openssl_encrypt($trainer->email, 'AES-256-CBC', $encryptionKey, 0, $iv);
            $encryptedRating = openssl_encrypt($trainer->rating, 'AES-256-CBC', $encryptionKey, 0, $iv);

            // Create a row with the encrypted values and the IV
            $row = WriterEntityFactory::createRowFromArray([
                base64_encode($encryptedFirstName),
                base64_encode($encryptedLastName),
                base64_encode($encryptedBranchId),
                base64_encode($encryptedPhoneNumber),
                base64_encode($encryptedEmail),
                base64_encode($encryptedRating),
                base64_encode($iv),
            ]);
            $writer->addRow($row);
        }

        // Close the writer to save the file
        $writer->close();

        // Return a response to indicate that the file has been saved
        $response = new BinaryFileResponse($filePath);
        $response->deleteFileAfterSend(true);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="trainers.xlsx"');
        return response()->json([
            'message' => 'تم إضافة البيانات بنجاح',
            'respponse'=>$response
        ]);
    }










    public function decryptExcel(Request $request)
    {
        // Get the file path of the uploaded file
        $filePath = 'D:/trainers.xlsx';

        // Get the decryption key from the input field
        $decryptionKey = $request->input('decryption_key');

        // Create a new Excel reader
        $reader = ReaderEntityFactory::createXLSXReader();

        // Open the file for reading
        $reader->open($filePath);

        $iv = '1234567890123456'; // يمكن تعديل القيمة

        // Create a new Excel writer
        $writer = WriterEntityFactory::createXLSXWriter();

        // Set the file path and name to save the file
        $newFilePath = 'D:/trainersdec.xlsx';
        $writer->openToFile($newFilePath);

        // Iterate over each row in the file
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                $rowData = $row->toArray(); // تحويل الكائن Row إلى مصفوفة

                // Decrypt each value using the decryption key
                $decryptedFirstName = openssl_decrypt($rowData[0], 'AES-256-CBC', $decryptionKey, 0, $iv);
                $decryptedLastName = openssl_decrypt($rowData[1], 'AES-256-CBC', $decryptionKey, 0, $iv);
                $decryptedBranchId = openssl_decrypt($rowData[2], 'AES-256-CBC', $decryptionKey, 0, $iv);
                $decryptedPhoneNumber = openssl_decrypt($rowData[3], 'AES-256-CBC', $decryptionKey, 0, $iv);
                $decryptedEmail = openssl_decrypt($rowData[4], 'AES-256-CBC', $decryptionKey, 0, $iv);
                $encryptedRating = openssl_decrypt($rowData[5], 'AES-256-CBC', $decryptionKey, 0, $iv);

                // Create a new row with the decrypted values
                $newRow = WriterEntityFactory::createRowFromArray([
                    $decryptedFirstName,
                    $decryptedLastName,
                    $decryptedBranchId,
                    $decryptedPhoneNumber,
                    $decryptedEmail,
                    $encryptedRating,
                ]);

                // Add the row to the writer
                $writer->addRow($newRow);
            }
        }

        // Close the reader and writer to release the file locks
        $reader->close();
        $writer->close();

        // Return a response to indicate that the file has been saved
        $response = new BinaryFileResponse($newFilePath);
        $response->deleteFileAfterSend(true);
        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="trainersdec.xlsx"');
        return $response;
    }



    public function exportExcel()
    {
        // Get the users data from the database
        $trainers = DB::table('trainer_profiles')
            ->join('users', 'trainer_profiles.user_id', '=', 'users.id')
            ->select('users.first_name', 'users.last_name','users.branch_id','users.phone_number', 'users.email', 'trainer_profiles.rating')
            ->get();
        // Create a new Excel writer
        $writer = WriterEntityFactory::createXLSXWriter();

        // Set the file path and name to save the file
        $filePath = 'D:/trainer_profiles.xlsx';
        $writer->openToFile($filePath);

        // Write the header row
        $headerRow = WriterEntityFactory::createRowFromArray([
            'first_name', 'last_name', 'branch_id', 'phone_number', 'email', 'rating'
        ]);
        $writer->addRow($headerRow);

        // Write the data rows
        foreach ($trainers as $trainer) {
            $row = WriterEntityFactory::createRowFromArray([
                $trainer->first_name,
                $trainer->last_name,
                $trainer->branch_id,
                $trainer->phone_number,
                $trainer->email,
                $trainer->rating,
            ]);
            $writer->addRow($row);
        }

        // Close the writer to save the file
        $writer->close();

        // Return a response to indicate that the file has been saved
        return response()->download($filePath, 'trainer_profiles.xlsx')->deleteFileAfterSend(true);

    }



}
