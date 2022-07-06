for ($i=117117117;$i<=117118117;$i++)
{
	my $pPin =  sprintf "%08d", rand 10_000_000;
	my $pCode = sprintf "%08d", $i;
	print "$pCode\t$pPin\n";
}
