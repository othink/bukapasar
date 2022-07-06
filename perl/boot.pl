use strict;
use DBI;
use HTTP::Status;
use HTTP::Response;
use LWP::UserAgent;
use URI::URL;
use vars qw($opt_h $opt_r $opt_H $opt_d $opt_p);
use Getopt::Std;
#my $ua = LWP::UserAgent->new;
#$ua->agent("MyApp/0.1 ");

my $ua = LWP::UserAgent->new(
    ssl_opts => { verify_hostname => 0 },
    protocols_allowed => ['https'],
);

# Variables for db connection
#my $dbname = "bukh7281_db1";
#my $username = "bukh7281_user";
#my $password = "Bismillah11!!";

my $dbname = "bukapasar_db1";
my $username = "root";
my $password = "";
# Connecting to database using DBI
#my $hDBConn = DBI->connect("DBI:mysql:$dbname",$username,$password);
#-------------------------------------------------------------------------#



print "BOOT MULAI :\n";


bootData();

#======================================================================
# Perhitungan Bonus Sponsor
#======================================================================
sub bootData{
    my $filename = 'iumkdataaddr.txt';
    open(my $fh, '<:encoding(UTF-8)', $filename) or die "Could not open file '$filename' $!";
    my $nr = 1;
    while (my $row = <$fh>) {
        my @fields = split /#/, $row;
        my $username = replace("/./","",$fields[0]);
        my $phone = replace(" ", "", $fields[1]);
        my $name = $fields[2];
        my $lastdigit = substr $phone, -6;
        my $random_number = rand(3);
        my $usernamex = $username.''.$lastdigit;
        my $email = $usernamex.'@gmail.com';
        my $alamat_lengkap= $fields[9];
        my $remark = $fields[4].'#'.$fields[5].'#'.$fields[6].'#'.$fields[7].'#'.$fields[8];
        if(length($phone) < 14){
            print "INSERT INTO `rb_konsumen` (`username`, `password`, `nama_lengkap`, `email`,`no_hp`,`alamat_lengkap`,`token`, `referral_id`, `wa_boot`,`remark`,`tanggal_daftar`) VALUES ('$usernamex', '3e39b3844837bdefc8017fbcb386ea302af877fb17baa09d0a1bd34b67bbf2b34fba314bbcab450f5f3f73771b7aea956ba3320defda029723f4fdff7dfa007b', '$name', '$email', '$phone','$alamat_lengkap', 'Y', 4, 0,'$remark','2021-03-18 01:30:00');\n";
            #print "$nr $username$lastdigit $phone\n";

            #my $req = HTTP::Request->new(POST => "https://www.bukapasar.com/perl/data.php?username=$usernamex&phone=$phone&name=$name&email=$email&alamat_lengkap=$alamat_lengkap&remark=$remark");
            #$req->content_type("application/x-www-form-urlencoded");
            #$req->content("query=libwww-perl&mode=dist");
            
            # Pass request to the user agent and get a response back
            #my $respon = $ua->request($req);
            # Check the outcome of the response
            #if ($respon->is_success)
            #{
            #    print $respon->content;
            #}
            #else
            #{
            #    print $respon->status_line, "\n";
            #}
        }
        
        $nr++;
    }
}
sub replace {
      my ($from,$to,$string) = @_;
      $string =~s/$from/$to/ig;                          #case-insensitive/global (all occurrences)
      return $string;
}
#$hDBConn->disconnect();

