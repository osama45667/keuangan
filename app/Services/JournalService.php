<?php

namespace App\Services;

use App\Models\Journal;
use App\Models\Attachment;
use Illuminate\Support\Facades\DB;

class JournalService
{
    public function create(array $data, array $lines, $files, int $userId): Journal
    {
        return DB::transaction(function () use ($data, $lines, $files, $userId) {
            $data['nomor_jurnal'] = $this->generateJournalNumber($data['tanggal']);
            $data['created_by'] = $userId;

            $journal = Journal::create($data);

            foreach ($lines as $line) {
                $line['created_by'] = $userId;
                $journal->lines()->create($line);
            }

            if ($files) {
                foreach ($files as $file) {
                    $path = $file->store('journals', 'private');
                    Attachment::create([
                        'journal_id' => $journal->id,
                        'original_name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                        'created_by' => $userId,
                    ]);
                }
            }

            return $journal;
        });
    }

    public function update(Journal $journal, array $data, array $lines, $files, int $userId): Journal
    {
        return DB::transaction(function () use ($journal, $data, $lines, $files, $userId) {
            $data['updated_by'] = $userId;
            $journal->update($data);

            $journal->lines()->update(['deleted_by' => $userId]);
            $journal->lines()->delete();
            foreach ($lines as $line) {
                $line['created_by'] = $userId;
                $journal->lines()->create($line);
            }

            if ($files) {
                foreach ($files as $file) {
                    $path = $file->store('journals', 'private');
                    Attachment::create([
                        'journal_id' => $journal->id,
                        'original_name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime' => $file->getClientMimeType(),
                        'size' => $file->getSize(),
                        'created_by' => $userId,
                    ]);
                }
            }

            return $journal;
        });
    }

    private function generateJournalNumber(string $tanggal): string
    {
        $ym = date('Ym', strtotime($tanggal));
        $last = Journal::where('nomor_jurnal', 'like', "JRNL-{$ym}-%")
            ->orderByDesc('id')
            ->first();

        $seq = 1;
        if ($last) {
            $parts = explode('-', $last->nomor_jurnal);
            $seq = intval(end($parts)) + 1;
        }

        return sprintf('JRNL-%s-%05d', $ym, $seq);
    }
}
