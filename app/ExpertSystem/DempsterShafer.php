<?php

namespace App\ExpertSystem;

class DempsterShafer
{
    public static function diagnose(array $evidence): array
    {
        // Frame of discernment (hypotheses)
        $frameOfDiscernment = ['diabetes_type_1', 'diabetes_type_2'];

        // Initial belief masses (assuming no prior knowledge)
        $beliefMasses = array_fill_keys($frameOfDiscernment, 0.5);

        foreach ($evidence as $item) {
            $currentBeliefMasses = $beliefMasses;
            foreach ($frameOfDiscernment as $hypothesis) {
                if ($item['is_'.$hypothesis]) {
                    // Belief in the evidence supporting the hypothesis
                    $support = $item['probability'] * $currentBeliefMasses[$hypothesis];
                    // Disbelief in the evidence weakening other hypotheses
                    $disbeliefWeight = $item['plausability'];
                    foreach ($frameOfDiscernment as $otherHypothesis) {
                        if ($otherHypothesis !== $hypothesis) {
                            $disbeliefContribution = $currentBeliefMasses[$otherHypothesis] * $disbeliefWeight;
                            $support += $disbeliefContribution;
                        }
                    }
                    $beliefMasses[$hypothesis] = $support;
                }
            }
            // Normalize belief masses
            $totalMass = array_sum($beliefMasses);
            foreach ($beliefMasses as &$mass) {
                $mass /= $totalMass;
            }
        }

        return $beliefMasses;
    }
}
