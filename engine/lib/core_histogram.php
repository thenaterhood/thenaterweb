<?php
/**
 *
 */

function getMaxLength( $values ){

	$maxLength = 0;

	foreach ( $values as $key => $val ){
		if ( strlen($key) > $maxLength ){
			$maxLength = strlen($key);
		}
	}

	return $maxLength;

}

function getMaxOccurances( $values ){
	$maxOccurances = 0;

	foreach ( $values as $key => $val ){
		if ( $val > $maxOccurances )
			$maxOccurances = $val;
	}

	return $maxOccurances;
}

function generateHistogram( $valuesIn ){

    $histogram = array();

    if ( count( $valuesIn ) < 1)
        return $histogram;

    $maxOccurances = getMaxOccurances( $valuesIn );
    if ( $maxOccurances < 1 )
        $maxOccurances = 1;

    $maxLength = getMaxLength( $valuesIn )+1;

    foreach ( $valuesIn as $item => $number ){

        $currOccurances = $valuesIn[$item];

        $asterisks = str_repeat("*", floor( ( (50 * $currOccurances) / $maxOccurances ) ) );

        $histoAdd = str_repeat('&nbsp;', $maxLength - strlen( $item ) ).$asterisks;

        $histogram[$item] = $histoAdd;

    }
    return $histogram;
}

function generateHistogramNospace( $valuesIn, $graphChar ){

    $histogram = array();

    if ( count( $valuesIn ) < 1)
        return $histogram;

    $maxOccurances = getMaxOccurances( $valuesIn );
    if ( $maxOccurances < 1 )
        $maxOccurances = 1;

    $maxLength = getMaxLength( $valuesIn )+1;

    foreach ( $valuesIn as $item => $number ){

        $currOccurances = $valuesIn[$item];

        $asterisks = str_repeat($graphChar, floor( ( (50 * $currOccurances) / $maxOccurances ) ) );

        //$histoAdd = str_repeat('&nbsp;', $maxLength - strlen( $item ) ).$asterisks;

        $histogram[$item] = $asterisks;

    }
    return $histogram;
}




?>