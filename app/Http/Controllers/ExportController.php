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
        $users = DB::table('users')->select('users.first_name', 'users.last_name', 'users.branch_id', 'users.phone_number', 'users.email')->get();

        // Get the encryption key from the input field
        $encryptionKey = $request->input('encryption_key');

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


        $iv = '1234567890123456'; // يمكن تعديل القيمة

        // Write the data rows
        foreach ($users as $user) {
            // Generate a random IV for each value

            // Encode each value using a password and a random IV
            $encryptedFirstName = openssl_encrypt($user->first_name, 'AES-256-CBC', $encryptionKey, 0, $iv);
            $encryptedLastName = openssl_encrypt($user->last_name, 'AES-256-CBC', $encryptionKey, 0, $iv);
            $encryptedBranchId = openssl_encrypt($user->branch_id, 'AES-256-CBC', $encryptionKey, 0, $iv);
            $encryptedPhoneNumber = openssl_encrypt($user->phone_number, 'AES-256-CBC', $encryptionKey, 0, $iv);
            $encryptedEmail = openssl_encrypt($user->email, 'AES-256-CBC', $encryptionKey, 0, $iv);

            // Create a row with the encrypted values and the IV
            $row = WriterEntityFactory::createRowFromArray([
                base64_encode($encryptedFirstName),
                base64_encode($encryptedLastName),
                base64_encode($encryptedBranchId),
                base64_encode($encryptedPhoneNumber),
                base64_encode($encryptedEmail),
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
        $response->headers->set('Content-Disposition', 'attachment; filename="users.xlsx"');
        return response()->json([
            'message' => 'تم إضافة البيانات بنجاح',
            'respponse'=>$response
        ]);
    }










    public function decryptExcel(Request $request)
    {
        // Get the file path of the uploaded file
        $filePath = 'D:/users.xlsx';

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
        $newFilePath = 'D:/usersdec.xlsx';
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

                // Create a new row with the decrypted values
                $newRow = WriterEntityFactory::createRowFromArray([
                    $decryptedFirstName,
                    $decryptedLastName,
                    $decryptedBranchId,
                    $decryptedPhoneNumber,
                    $decryptedEmail,
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
        $response->headers->set('Content-Disposition', 'attachment; filename="usersdec.xlsx"');
        return $response;
    }



}
