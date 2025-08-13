<?php
namespace App\Helpers;

class MetricsCalculator {
    public static function calculateMetrics($actualLabels, $predictedLabels) {
        $truePositive = ['positif' => 0, 'negatif' => 0, 'netral' => 0];
        $falsePositive = ['positif' => 0, 'negatif' => 0, 'netral' => 0];
        $falseNegative = ['positif' => 0, 'negatif' => 0, 'netral' => 0];
        $trueNegative = ['positif' => 0, 'negatif' => 0, 'netral' => 0];

        $total = count($actualLabels);

        for ($i = 0; $i < $total; $i++) {
            foreach (['positif', 'negatif', 'netral'] as $class) {
                if ($actualLabels[$i] == $class && $predictedLabels[$i] == $class) {
                    $truePositive[$class]++;
                } elseif ($actualLabels[$i] != $class && $predictedLabels[$i] == $class) {
                    $falsePositive[$class]++;
                } elseif ($actualLabels[$i] == $class && $predictedLabels[$i] != $class) {
                    $falseNegative[$class]++;
                } elseif ($actualLabels[$i] != $class && $predictedLabels[$i] != $class) {
                    $trueNegative[$class]++;
                }
            }
        }

        $precision = [];
        $recall = [];
        $accuracy = [];

        foreach (['positif', 'negatif', 'netral'] as $class) {
            $precision[$class] = $truePositive[$class] / max(1, ($truePositive[$class] + $falsePositive[$class]));
            $recall[$class] = $truePositive[$class] / max(1, ($truePositive[$class] + $falseNegative[$class]));
            $accuracy[$class] = ($truePositive[$class] + $trueNegative[$class]) / $total;
        }

        $overallAccuracy = array_sum($truePositive) / $total;

        return [
            'precision' => $precision,
            'recall' => $recall,
            'accuracy' => $accuracy,
            'overallAccuracy' => $overallAccuracy
        ];
    }
}
