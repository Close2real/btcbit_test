<?php

class NoteTranscriptionService
{
    const LOWEST_TRANSCRIPTION = [-3, 10];
    const HIGHEST_TRANSCRIPTION = [5, 1];
    const LOWEST_NOTE = 1;
    const HIGHEST_NOTE = 12;

    /**
     * @var array
     */
    private array $transposedNoteTranscription;

    /**
     * @param array $transcriptedNotesArray
     * @param int $transpose
     * @return array
     * @throws Exception
     */
    public function transposeTranscriptedNoteArray(array $transcriptedNotesArray, int $transpose): array
    {
        $transposedNotes = [];

        foreach ($transcriptedNotesArray as $noteTranscription)
        {
            $this->transposedNoteTranscription = $noteTranscription;

            if($this->checkTranspose($noteTranscription)) {
                $transposedNotes[] = $this->getTransposedNote($noteTranscription, $transpose);
            }
        }

        return $transposedNotes;
    }

    /**
     * @param array $noteTranscription
     * @param int $transpose
     * @return array
     * @throws Exception
     */
    private function getTransposedNote(array $noteTranscription, int $transpose): array
    {
        $octave = $noteTranscription[0];
        $note = $noteTranscription[1];

        $transposedNote = $note + $transpose;
        $transposedOctave = $octave;

        if($transposedNote % self::HIGHEST_NOTE == 0) {
            $transposedOctave = $octave + intdiv($transposedNote, self::HIGHEST_NOTE) - 1;
            $transposedNote = self::HIGHEST_NOTE;
        } else {
            if($transposedNote > self::HIGHEST_NOTE) {
                $transposedOctave = $octave + intdiv($transposedNote, self::HIGHEST_NOTE);
                $transposedNote = $transposedNote % self::HIGHEST_NOTE;
            } elseif ($transposedNote < self::LOWEST_NOTE) {
                $transposedOctave = $octave + intdiv($transposedNote, self::HIGHEST_NOTE) - 1;
                $transposedNote = self::HIGHEST_NOTE + $transposedNote % self::HIGHEST_NOTE;
            }
        }

        $transposedArray = [$transposedOctave, $transposedNote];

        $this->checkTranspose($transposedArray);

        return $transposedArray;
    }

    /**
     * @param array $noteTranscription
     * @return bool
     * @throws Exception
     */
    private function checkTranspose(array $noteTranscription): bool
    {
        $failedNote = "Note: [" . $this->transposedNoteTranscription[0] .", " . $this->transposedNoteTranscription[1] . "].";


        if(count($noteTranscription) > 2) {
            throw new Exception("$failedNote You can't have more than 2 entries in note transcription");
        }

        if($noteTranscription[0] > self::HIGHEST_TRANSCRIPTION[0]) {
            throw new Exception("$failedNote Your octave is too high for it to be transposed");
        }

        if($noteTranscription[0] < self::LOWEST_TRANSCRIPTION[0]) {
            throw new Exception("$failedNote Your octave is too low for it to be transposed");
        }

        if($noteTranscription[1] > self::HIGHEST_NOTE) {
            throw new Exception("$failedNote Your note is too high. Please fix your input");
        }

        if($noteTranscription[1] < self::LOWEST_NOTE) {
            throw new Exception("$failedNote Your note is too low. Please fix your input");
        }

        if($this->checkHigherThanHighestTranscriptionLevel($noteTranscription)) {
            throw new Exception("$failedNote Transposition failed. 
            Please change the note transcription to lower to proceed further");
        }

        if($this->checkLowerThanLowestTranscriptionLevel($noteTranscription)) {
            throw new Exception("$failedNote Transposition failed. 
            Please change the note transcription to higher to proceed further");
        }

        return true;
    }

    /**
     * @param array $transcriptedNote
     * @return bool
     */
    private function checkHigherThanHighestTranscriptionLevel(array $transcriptedNote)
    {
        if($transcriptedNote[0] == self::HIGHEST_TRANSCRIPTION[0]
            && $transcriptedNote[1] > self::HIGHEST_TRANSCRIPTION[1]) {
            return true;
        }

        return false;
    }

    /**
     * @param array $transcriptedNote
     * @return bool
     */
    private function checkLowerThanLowestTranscriptionLevel(array $transcriptedNote)
    {
        if($transcriptedNote[0] == self::LOWEST_TRANSCRIPTION[0]
            && $transcriptedNote[1] < self::LOWEST_TRANSCRIPTION[1]) {
            return true;
        }

        return false;
    }
}