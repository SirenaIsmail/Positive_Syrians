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
        // يجب تعديله بحيث يأخذ المدربين الأكثر تقييماً
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

        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('AES-256-CBC'));

        // Write the data rows
        foreach ($users as $user) {
            // Encode each value using a password
            $encryptedFirstName = openssl_encrypt($user->first_name, 'AES-256-CBC', $encryptionKey, 0, 'my_secret_iv');
            $encryptedLastName = openssl_encrypt($user->last_name, 'AES-256-CBC', $encryptionKey, 0, 'my_secret_iv');
            $encryptedBranchId = openssl_encrypt($user->branch_id, 'AES-256-CBC', $encryptionKey, 0, 'my_secret_iv');
            $encryptedPhoneNumber = openssl_encrypt($user->phone_number, 'AES-256-CBC', $encryptionKey, 0, 'my_secret_iv');
            $encryptedEmail = openssl_encrypt($user->email, 'AES-256-CBC', $encryptionKey, 0, 'my_secret_iv');

            // Create a row with the encrypted values
            $row = WriterEntityFactory::createRowFromArray([
                $encryptedFirstName,
                $encryptedLastName,
                $encryptedBranchId,
                $encryptedPhoneNumber,
                $encryptedEmail,
            ]);
            $writer->addRow($row);
        }

        // Close the writer to save the file
        $writer->close();

        // Return a response to indicate that the file has been saved
        return response()->download($filePath, 'users.xlsx')->deleteFileAfterSend(true);
    }


    public function decryptExcel(Request $request)
    {
        // Get the file path of the uploaded file
        $filePath = $request->file('excel_file')->getPathname();

        // Get the decryption key from the input field
        $decryptionKey = $request->input('decryption_key');

        // Create a new Excel reader
        $reader = ReaderEntityFactory::createXLSXReader();

        // Open the file for reading
        $reader->open($filePath);

        // Iterate over each row in the file
        foreach ($reader->getSheetIterator() as $sheet) {
            foreach ($sheet->getRowIterator() as $row) {
                // Decrypt each value using the decryption key
                $decryptedFirstName = openssl_decrypt($row[0], 'AES-256-CBC', $decryptionKey, 0, 'my_secret_iv');
                $decryptedLastName = openssl_decrypt($row[1], 'AES-256-CBC', $decryptionKey, 0, 'my_secret_iv');
                $decryptedBranchId = openssl_decrypt($row[2], 'AES-256-CBC', $decryptionKey, 0, 'my_secret_iv');
                $decryptedPhoneNumber = openssl_decrypt($row[3], 'AES-256-CBC', $decryptionKey, 0, 'my_secret_iv');
                $decryptedEmail = openssl_decrypt($row[4], 'AES-256-CBC', $decryptionKey, 0, 'my_secret_iv');

                // Create a new row with the decrypted values
                $newRow = [
                    $decryptedFirstName,
                    $decryptedLastName,
                    $decryptedBranchId,
                    $decryptedPhoneNumber,
                    $decryptedEmail,
                ];

                // Print the decrypted values to the console
                print_r($newRow);
            }
        }

        // Close the reader to release the file lock
        $reader->close();
    }


}
