<?php

namespace App\Services;

use App\Models\Symptom;

class DiabetesDetectorService
{
    /**
     * Calculate the belief function for each symptom.
     *
     * @param array $symptomsData
     * @return array
     */
    public function calculateBeliefFunctions(array $symptomsData): array
    {
        $beliefFunctions = [];

        foreach ($symptomsData as $symptom) {
            // Calculate belief function based on expert knowledge or historical data
            // For simplicity, I'll assume that probability values are stored in the 'probability' column of the 'symptoms' table
            $beliefFunctions[$symptom['id']] = $symptom['probability'];
        }

        return $beliefFunctions;
    }

    /**
     * Combine evidence using Dempster's rule of combination.
     *
     * @param array $beliefFunctions
     * @return array
     */
    public function combineEvidence(array $beliefFunctions): array
    {
        // Initialize the combined mass function
        $combinedMassFunction = [];

        // Perform Dempster's rule of combination
        foreach ($beliefFunctions as $beliefFunction) {
            // Combine the belief functions using Dempster's rule
            // For simplicity, I'll assume equal weight for all pieces of evidence
            foreach ($combinedMassFunction as $key => $value) {
                $combinedMassFunction[$key] += $value * (1 - $beliefFunction);
            }
            $combinedMassFunction[$beliefFunction] = 1 - array_product($beliefFunctions);
        }

        return $combinedMassFunction;
    }

    /**
     * Determine diabetes type based on combined mass function.
     *
     * @param array $combinedMassFunction
     * @return string|null
     */
    public function determineDiabetesType(array $combinedMassFunction): ?string
    {
        // Calculate belief and plausibility for each diabetes type
        // Here, you can implement a threshold or choose the type with the highest belief value as the result

        // For demonstration, I'll return the type with the highest belief value
        $maxBelief = max($combinedMassFunction);
        $diabetesType = array_search($maxBelief, $combinedMassFunction);

        return $diabetesType;
    }

    /**
     * Detect diabetes type for a given user.
     *
     * @param int $userId
     * @return string|null
     */
    public function detectDiabetesType(int $userId): ?string
    {
        // Retrieve symptoms data for the user from the database
        $symptomsData = Symptom::where('user_id', $userId)->get()->toArray();

        // Calculate belief functions for each symptom
        $beliefFunctions = $this->calculateBeliefFunctions($symptomsData);

        // Combine evidence using Dempster's rule
        $combinedMassFunction = $this->combineEvidence($beliefFunctions);

        // Determine diabetes type based on combined mass function
        $diabetesType = $this->determineDiabetesType($combinedMassFunction);

        return $diabetesType;
    }
}
