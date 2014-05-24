<?php

include "../autoload.php";

function printDiff($a, $b) {
	printf("want: ");
		for ($i = 0; $i < 32; $i++) printf("%02x,", $a[$i]); printf("\n");
	printf("got : ");
		for ($i = 0; $i < 32; $i++) printf("%02x,", $b[$i]); printf("\n");
	printf("diff: ");
		for ($i = 0; $i < 32; $i++) {
			if ($a[$i] ^ $b[$i]) {
				printf("%02x,", $a[$i] ^ $b[$i]);
			} else {
				printf("  ,");
			}
		}
	printf("\n\n");
}

$time = -microtime(true);

$salt = new Salt();

$alice = $salt->crypto_box_keypair();
$alice_sk = $alice[0];
$alice_pk = $alice[1];

$bob = $salt->crypto_box_keypair();
$bob_sk = $alice[0];
$bob_pk = $alice[1];

$alice_shared = $salt->crypto_scalarmult($alice_sk, $bob_pk);

$bob_shared   = $salt->crypto_scalarmult($bob_sk, $alice_pk);

if ($salt->compare($alice_shared, $bob_shared) !== 1) {
	printDiff($alice_shared, $bob_shared);
}

$time += microtime(true);

printf("done\n");
printf("microtime: %f\n", $time);
printf("memory peak: %s\n", memory_get_peak_usage(true));

