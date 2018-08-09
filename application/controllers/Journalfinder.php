<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Journalfinder extends CI_Controller {
	
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function __construct() {
          parent::__construct();
          $this->load->model(array('Data'));
          $this->load->helper(array('url'));
    }
	public function index()
	{
		$this->load->view('template/header');
		$this->load->view('pages/home');
		$this->load->view('template/footer');
	}

	public function jurnalStemmer(){ //proses stemming jurnal dan merubah menjadi bentuk array
		$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
		$stopwordFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
		$stopword = $stopwordFactory->createStopWordRemover();
        $stemmer  = $stemmerFactory->createStemmer();
        $date = date("Y-m-d");
		$artikel = $this->Data->getArtikel($date);
		foreach ($artikel->result() as $key) {
			$title = $key -> title;
			$keyword = $key -> deskriptor;
			$abstract = $key -> sari;
			
			$titleStem = $stemmer->stem($title);
			$keywordStem = $stemmer->stem($keyword);
			$abstractStem = $stemmer->stem($abstract);

			$titleRemove = $stopword->remove($titleStem);
			$keywordRemove = $stopword->remove($keywordStem);
			$abstractRemove = $stopword->remove($abstractStem);
			
			$data[$key->id_jurnal] = array(
				'id_jurnal' => $key->id_jurnal,
				'id_direktori' => $key->id_direktori,
				'title' => $titleRemove,
				'abstrak' => $abstractRemove,
				'keywords' => $keywordRemove,
				'tanggal'=>$key->tanggal
			);
			
		}
		$jsondata= json_encode($data);
		$filename = 'datapdf.json';
		$handle = fopen($filename,'w+');
		fwrite($handle, $jsondata);
		fclose($handle);
	}


	public function similarity($data){ //abstract user dibandingkan dengan judul, abstract dan keyword pada artikel
		ini_set('memory_limit', '-1');
		$datapdf = json_decode(file_get_contents('datapdf.json'), True);
		foreach ($datapdf as $key) {
			
			$titleSplit = explode(" ", $key['title']);
			$keywordSplit = explode(" ", $key['keywords']);
			$abstractSplit = explode(" ", $key['abstrak']);

			$titleClear = array_unique($titleSplit);
			$keywordClear = array_unique($keywordSplit);
			$abstractClear = array_unique($abstractSplit);

			sort($titleClear);
			sort($keywordClear);
			sort($abstractClear);

			$judTitle = 0;
			$judAbstract = 0;
			$judKeyword = 0;
			$absTitle = 0;
			$absAbstract = 0;
			$absKeyword = 0;
			$keyTitle = 0;
			$keyAbstract = 0;
			$keyKeyword = 0;
			$bidTitle = 0;
			$bidAbstract = 0;
			$bidKeyword = 0;

			for($x=0;$x<count($data);$x++){
				if($x==0){
					for($y=0;$y<3;$y++){
						if ($y==0) { // judul dengan judul
							$judTitle = array_intersect($data[$x], $titleClear); //mencari kata yang sama
							$arr_union = array_merge($data[$x], $titleClear); // penggabungan
							$totJudTitle = (count($judTitle) / (count($arr_union)))*2; //jaccard algorithm
							
						}elseif ($y==1) { // judul dengan abstrak
							$judAbstract = array_intersect($data[$x], $abstractClear);
							$arr_union = array_merge($data[$x],$abstractClear);
							$totJudAbstract = (count($judAbstract) / (count($arr_union)))*2; //jaccard algorithm
							
						}elseif ($y==2) { // judul dengan keyword
							$judKeyword = array_intersect($data[$x], $keywordClear);
							$arr_union = array_merge($data[$x],$keywordClear);
							$totJudKeyword = (count($judKeyword) / (count($arr_union)))*2; //jaccard algorithm
						}
					}
				}

				if($x==1){
					for($y=0;$y<3;$y++){
						if ($y==0) { // abstrak dengan judul
							$absTitle = array_intersect($data[$x], $titleClear); //mencari kata yang sama
							$arr_union = array_merge($data[$x], $titleClear); // penggabungan
							$totAbsTitle = (count($absTitle) / (count($arr_union)))*2; //jaccard algorithm
							
						}elseif ($y==1) { // abstrak dengan abstrak
							$absAbstract = array_intersect($data[$x], $abstractClear);
							$arr_union = array_merge($data[$x],$abstractClear);
							$totAbsAbstract = (count($absAbstract) / (count($arr_union)))*2; //jaccard algorithm
							
						}elseif ($y==2) { // abstrak dengan keyword
							$absKeyword = array_intersect($data[$x], $keywordClear);
							$arr_union = array_merge($data[$x],$keywordClear);
							$totAbsKeyword = (count($absKeyword) / (count($arr_union)))*2; //jaccard algorithm
							
						}
					}
				}

				if($x==2){
					for($y=0;$y<3;$y++){
						if ($y==0) { // keyword dengan judul
							$keyTitle = array_intersect($data[$x], $titleClear);
							$arr_union = array_merge($data[$x], $titleClear);
							$totKeyTitle = (count($keyTitle) / (count($arr_union)))*2; //jaccard algorithm
							
						}elseif ($y==1) { // keyword dengan abstrak
							$keyAbstract = array_intersect($data[$x], $abstractClear);
							$arr_union = array_merge($data[$x], $abstractClear);
							$totKeyAbstract = (count($keyAbstract) / (count($arr_union)))*2; //jaccard algorithm
							
						}elseif ($y==2) { // keyword dengan keyword
							$keyKeyword = array_intersect($data[$x], $keywordClear);
							$arr_union = array_merge($data[$x],$keywordClear);
							$totKeyKeyword = (count($keyKeyword) / (count($arr_union)))*2; //jaccard algorithm
							
						}
					}
				}

				if($x==3){
					for($y=0;$y<3;$y++){
						if ($y==0) { // bidang dengan judul
							$bidTitle = array_intersect($data[$x],$titleClear);
							$arr_union = array_merge($data[$x],$titleClear);
							$totBidTitle = (count($bidTitle) / (count($arr_union)))*2; //jaccard algorithm
							
						}elseif ($y==1) { // bidang dengan abstrak
							$bidAbstract = array_intersect($data[$x], $abstractClear);
							$arr_union = array_merge($data[$x],$abstractClear);
							$totBidAbstract = (count($bidAbstract) / (count($arr_union)))*2; //jaccard algorithm
							
						}elseif ($y==2) { // bidang dengan keyword
							$bidKeyword = array_intersect($data[$x], $keywordClear);
							$arr_union = array_merge($data[$x], $keywordClear);
							$totBidKeyword = (count($bidKeyword) / (count($arr_union)))*2; //jaccard algorithm

						}
					}
				}
			}

			//hasil perbandingan antara judul user dengan seluruh data jurnal
			$totalsteptwo[$key['id_jurnal']] = (($totJudTitle*80) + ($totJudAbstract * 10) + ($totJudKeyword * 10 ));
			//hasil perbandingan antara abstrak user dengan seluruh data jurnal
			$totalstepthree[$key['id_jurnal']] = (($totAbsTitle*10) + ($totAbsAbstract * 80) + ($totAbsKeyword * 10 ));
			//hasil perbandingan antara keyword user dengan seluruh data jurnal
			$totalstepfour[$key['id_jurnal']] = (($totKeyTitle*10)+($totKeyAbstract*10) + ($totKeyKeyword * 80));
			//hasil perbandingan antara bidang user dengan seluruh data jurnal
			$totalstepfive[$key['id_jurnal']] = (($totBidTitle*30)+($totBidAbstract*20) + ($totBidKeyword * 50));

			//hasil seluruh step pada masing-masing jurnal
			$totalallstep[$key['id_jurnal']] = (($totalsteptwo[$key['id_jurnal']]*5)+($totalstepthree[$key['id_jurnal']]*70)+($totalstepfour[$key['id_jurnal']]*10)+($totalstepfive[$key['id_jurnal']])*5)/100;
		}

		//$result = array($totalallstep,$finaltotalallstep,$totalstepthree,$totalstepfour, $totalstepfive);
		return $totalallstep;
	}

	//melakukan perulangan yang bertujuan untuk menghitung rata-rata jika ada jurnal yang memiliki beberapa artikel
	public function averageJurnal($result){
		$date = date("Y-m-d");
		$artikel = $this->Data->getArtikel($date);
		foreach ($artikel->result() as $key) {
			if(empty($finaltotalallstep[$key->id_direktori])){
				$finaltotalallstep[$key->id_direktori] = $result[$key->id_jurnal];
			}else{
				if($finaltotalallstep[$key->id_direktori] < $result[$key->id_jurnal]){
					$finaltotalallstep[$key->id_direktori] = $result[$key->id_jurnal];
				}else{
					$finaltotalallstep[$key->id_direktori] = $finaltotalallstep[$key->id_direktori];
				}
			}
		}
		return $finaltotalallstep;
	}

	public function search(){
		$stemmerFactory = new \Sastrawi\Stemmer\StemmerFactory();
		$stopwordFactory = new \Sastrawi\StopWordRemover\StopWordRemoverFactory();
		$stopword = $stopwordFactory->createStopWordRemover();
        $stemmer  = $stemmerFactory->createStemmer();

        //mengambil inputan user
		$judul = $this->input->post('judul');
		$abstrak = $_POST['abstrak'];
		$keyword = $_POST['keyword'];
		if(!empty($_POST['bidang']))
			$bidangclear = $_POST['bidang'];
		else
			$bidangclear = " ";

		//stemming inputan user
		$judulstem = $stemmer->stem($judul);
		$abstrakstem =$stemmer->stem($abstrak);
		$keywordstem = $stemmer->stem($keyword);

		//menghilangkan kata tidak penting pada inputan user
		$judulremove = $stopword->remove($judulstem);
		$abstrakremove = $stopword->remove($abstrakstem);
		$keywordremove = $stopword->remove($keywordstem);

		//memisahkan inputan user berdasarkan whitespace dan dijadikan sebuah array
		$judulsplit = explode(" ", $judulremove);
		$abstraksplit = explode(" ", $abstrakremove);
		$keywordsplit = explode(" ", $keywordremove);

		//menghilangkan kata yang duplikat
		$judulclear = array_unique($judulsplit);
		$abstrakclear = array_unique($abstraksplit);
		$keywordclear = array_unique($keywordsplit);

		//menyortir array agar key nya dimulai dari 0 kembali
		sort($judulclear);
		sort($abstrakclear);
		sort($keywordclear);
		
		$data = array($judulclear,$abstrakclear,$keywordclear, $bidangclear);

		//update json
		$date = date("Y-m-d");
		$maxDateJurnal=$this->Data->getMaxDateJurnal($date)->result();
		$maxDate = (array) $maxDateJurnal[0];
		$jsonDate = strtotime(date("Y-F-d",filemtime("datapdf.json")));
		if(strtotime($maxDate['tanggal'])>$jsonDate){
			$this->jurnalStemmer();
		}
		
		//memanggil fungsi similarity dengan parameter data
		$result = $this->similarity($data);

		//memanggil fungsi averageJurnal yang bertujuan untuk menghitung rata rata jika ada jurnal yang memiliki beberapa artikel
		$totalallstep = $this->averageJurnal($result);
		//pengurutan total dari terbesar ke terkecil;
		arsort($totalallstep);
		$finaltotalallstep = array_slice($totalallstep, 0, 5, True);

		//penentuan jurnal mana yang akan ditampilkan sesuai dengan kriteria yg telah ditentukan
		$date = date("Y-m-d");
		$artikel = $this->Data->getArtikel($date);
		foreach ($finaltotalallstep as $key=>$val) { //perulangan sejumlah artikel

			if($val>=10){ //jurnal yang akan digunakan adalah jurnal yang memiliki tingkat kesamaan lebih dari 10%

				$jurnal= $this->Data->getJurnal($key); //mengambil data jurnal sesuai dengan id yang dipanggil

				if($jurnal['judul'] != ""){
					if(!empty($datafinal[$key]['totalstep'])){ //kondisi ini dilakukan agar jika terdapat jurnal yang memiliki beberapa artikel, maka semua data artikel itu akan dimasukan ke dalam data jurnal
						
							$deskriptor = $stopword->remove($jurnal['deskriptor']);
							$stem = $stemmer->stem($deskriptor);
							$trim = rtrim($stem,"-");
							$subject = explode("-", $trim);
							$datafinal[$key] = array(
								'judul' => $jurnal['judul'],
								'editor' => $jurnal['editor'],
								'penerbit' => $jurnal['penerbit'],
								'alamat' => $jurnal['alamat'],
								'deskriptor' => $subject,
								'match' => round($finaltotalallstep[$key],2),
								'action' => '<a onclick="" class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit"><i class="glyphicon glyphicon-pencil"></i> Detail</a>'
							);
					}else{
						$deskriptor = $stopword->remove($jurnal['deskriptor']);
						$stem = $stemmer->stem($deskriptor);
						$trim = rtrim($stem,"-");
						$subject = explode("-", $trim);
						$datafinal[$key] = array(
							'judul' => $jurnal['judul'],
							'editor' => $jurnal['editor'],
							'penerbit' => $jurnal['penerbit'],
							'alamat' => $jurnal['alamat'],
							'deskriptor' => $subject,
							'match' => round($finaltotalallstep[$key],2),
							'action' => '<a onclick="" class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit"><i class="glyphicon glyphicon-pencil"></i> Detail</a>'
						);
					}
				}
			}
		}

		if(isset($datafinal)){ 
			$datafix['data'] = $datafinal;
			$this->load->view('template/header');
	   		$this->load->view('pages/result',$datafix);
	   		$this->load->view('template/footer');
		}else{
			$this->load->view('template/header');
	   		$this->load->view('pages/noresult');
	   		$this->load->view('template/footer');
		}

   	}
}
