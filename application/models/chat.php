<?
/* *******************************************************************************************************
MODUL NAME 			: MTSN LAWANG
FILE NAME 			:
AUTHOR				:
VERSION				: 1.0
MODIFICATION DOC	:
DESCRIPTION			:
***************************************************************************************************** */

/***
 * Entity-base class untuk mengimplementasikan tabel kategori.
 *
 ***/
include_once(APPPATH . '/models/Entity.php');

class Chat extends Entity
{

	var $query;
	/**
	 * Class constructor.
	 **/
	function Chat()
	{
		$this->Entity();
	}

	function insert()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		//$this->setField("JAM", $this->getNextId("JAM","CHAT"));

		$str = "
				INSERT INTO CHAT (
				   JAM, NAMA, PESAN,
   					IP_ADDRESS, PEGAWAI_ID, HALAMAN, KODE, WAKTU)
				VALUES (
				  '" . $this->getField("JAM") . "',
				  '" . $this->getField("NAMA") . "',
				  '" . $this->getField("PESAN") . "',
				  '" . $this->getField("IP_ADDRESS") . "',
				  '" . $this->getField("PEGAWAI_ID") . "',
				  '" . $this->getField("HALAMAN") . "',
				  '" . $this->getField("KODE") . "',
				  CURRENT_TIMESTAMP
				)";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function update()
	{
		/*Auto-generate primary key(s) by next max value (integer) */
		$str = "UPDATE CHAT SET
				  NAMA = '" . $this->getField("NAMA") . "'
				WHERE JAM = '" . $this->getField("JAM") . "'
				";
		$this->query = $str;
		return $this->execQuery($str);
	}

	function delete()
	{
		$str = "DELETE FROM CHAT
                WHERE
                  JAM = '" . $this->getField("JAM") . "'";

		$this->query = $str;
		return $this->execQuery($str);
	}

	function deleteParentChild()
	{
		$str = "DELETE FROM CHAT
                WHERE
                  NAMA = '" . $this->getField("NAMA") . "'";

		$this->query = $str;
		return $this->execQuery($str);
	}

	/**
	 * Cari record berdasarkan array parameter dan limit tampilan
	 * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy")
	 * @param int limit Jumlah maksimal record yang akan diambil
	 * @param int from Awal record yang diambil
	 * @return boolean True jika sukses, false jika tidak
	 **/
	function selectByParams($paramsArray = array(), $limit = -1, $from = -1, $statement = '', $order = ' ORDER BY JAM ASC ')
	{
		$str = "SELECT JAM, NAMA, PESAN,
   					IP_ADDRESS, PEGAWAI_ID, HALAMAN, KODE, TO_CHAR(WAKTU, 'DD/MM/YYYY HH24:MI:SS') WAKTU
				FROM CHAT A WHERE 1=1 ";
		//JAM IS NOT NULL
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsRekanan($paramsArray = array(), $limit = -1, $from = -1, $statement = '', $order = ' ORDER BY B.NAMA ASC ')
	{
		$str = "SELECT A.NAMA KODE, (SELECT X.NAMA FROM REKANAN_TIPE X WHERE X.REKANAN_TIPE_ID = B.REKANAN_TIPE_ID)|| '. ' || B.NAMA NAMA
				FROM CHAT A INNER JOIN REKANAN B ON A.NAMA = B.KODE WHERE 1=1
				 ";
		//JAM IS NOT NULL
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . " GROUP BY A.NAMA, B.NAMA, B.REKANAN_TIPE_ID " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsTerakhir($paramsArray = array(), $limit = -1, $from = -1, $statement = '', $order = ' ORDER BY JAM ASC ')
	{
		$str = "SELECT A.PEGAWAI_ID, A.NAMA, B.NAMA PEGAWAI, B.JABATAN, C.NAMA CABANG, A.JAM,A.PESAN,
                       A.IP_ADDRESS, A.HALAMAN, A.KODE, TO_CHAR(WAKTU, 'DD-MM-YYYY HH24:MI:SS') WAKTU
                FROM CHAT_TERAKHIR A
				INNER JOIN LINK.PEGAWAI B ON A.PEGAWAI_ID = B.PEGAWAI_ID
				INNER JOIN LINK.CABANG C ON B.CABANG_ID = C.CABANG_ID
                    WHERE 1=1 ";
		//JAM IS NOT NULL
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsRekananAanwijzing($paramsArray = array(), $limit = -1, $from = -1, $statement = '', $order = ' ORDER BY NAMA ASC ')
	{
		$str = "SELECT A.KODE_REKANAN, A.KODE, A.NAMA, B.KODE_QR FROM
				(
				SELECT A.PEGAWAI_ID, A.NAMA KODE, D.KODE_REKANAN, (SELECT X.NAMA FROM REKANAN_TIPE X WHERE X.REKANAN_TIPE_ID = B.REKANAN_TIPE_ID)|| '. ' || B.NAMA NAMA, C.USER_LOGIN_ID
					FROM CHAT A
					INNER JOIN PAKET_REKANAN D ON A.NAMA = D.KODE_REKANAN
					INNER JOIN REKANAN B ON D.PEGAWAI_ID = B.PEGAWAI_ID
					INNER JOIN USER_LOGIN C ON C.PEGAWAI_ID = B.PEGAWAI_ID
					WHERE 1 = 1
				GROUP BY A.PEGAWAI_ID, A.NAMA, D.KODE_REKANAN,B.NAMA, B.REKANAN_TIPE_ID, C.USER_LOGIN_ID
				) A LEFT JOIN PAKET_AANWIJZING_VALIDASI B ON A.USER_LOGIN_ID = B.USER_LOGIN_ID AND A.PEGAWAI_ID = B.PEGAWAI_ID
				WHERE 1 = 1
				 ";
		//JAM IS NOT NULL
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$str .= $statement . "  " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}


	function selectByParamsKonfirmasi($paramsArray = array(), $limit = -1, $from = -1, $statement = '', $order = '')
	{
		$str = "
				SELECT PEGAWAI_ID, KODE, HALAMAN, INFORMASI, KODE_HALAMAN FROM
				(
				SELECT A.PEGAWAI_ID, A.NAMA KODE, HALAMAN, '' INFORMASI, A.KODE KODE_HALAMAN
				FROM CHAT A INNER JOIN PAKET_REKANAN B ON A.PEGAWAI_ID = B.PEGAWAI_ID AND A.NAMA = B.KODE_REKANAN WHERE 1 = 1 AND PESAN = 'CONFIRMED'
                UNION ALL
				SELECT PEGAWAI_ID, 'KEHADIRAN' KODE, TO_NUMBER(A.KODE_REKANAN, '9999999999') HALAMAN, TO_CHAR(COALESCE(AANWIJZING, 0), '9999999') INFORMASI, 0 KODE_HALAMAN
				FROM PAKET_REKANAN A INNER JOIN REKANAN B ON A.PEGAWAI_ID = B.PEGAWAI_ID  AND A.TANGGAL_DAFTAR IS NOT NULL AND A.LULUS_PENDAFTARAN = 1
				UNION ALL
                SELECT PEGAWAI_ID, 'PESAN' KODE, HALAMAN, INFORMASI, A.KODE KODE_HALAMAN
				FROM
				(
                SELECT A.PEGAWAI_ID, HALAMAN, A.KODE, COUNT(1), (SELECT COUNT(1) JUMLAH_REKANAN
			                    FROM CHAT X INNER JOIN PAKET_REKANAN Y ON X.PEGAWAI_ID = Y.PEGAWAI_ID AND X.NAMA = Y.KODE_REKANAN WHERE 1=1 AND NOT PESAN = 'CONFIRMED' AND X.PEGAWAI_ID = A.PEGAWAI_ID AND X.HALAMAN = A.HALAMAN AND X.KODE = A.KODE AND NOT JAM IS NULL)
                || '/' ||
                (SELECT COUNT(1) JUMLAH_REKANAN
                                FROM CHAT X LEFT JOIN PAKET_REKANAN Y ON X.PEGAWAI_ID = Y.PEGAWAI_ID AND X.NAMA = Y.KODE_REKANAN WHERE 1=1 AND NOT PESAN = 'CONFIRMED' AND PEGAWAI_ID IS NULL AND X.PEGAWAI_ID = A.PEGAWAI_ID AND X.HALAMAN = A.HALAMAN AND X.KODE = A.KODE AND NOT JAM IS NULL)
                INFORMASI
                FROM CHAT A INNER JOIN PAKET_REKANAN B ON A.PEGAWAI_ID = B.PEGAWAI_ID AND A.NAMA = B.KODE_REKANAN WHERE 1 = 1 AND NOT PESAN = 'CONFIRMED' AND NOT JAM IS NULL
                GROUP BY A.PEGAWAI_ID, HALAMAN, A.KODE
                ) A
                ) A WHERE 1 = 1
		";

		//JAM IS NOT NULL
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}
		$str .= $statement . " " . $order;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsKonfirmasiRekanan($paramsArray = array(), $limit = -1, $from = -1, $rekanan_kode = '', $order = '')
	{
		$str = "
				SELECT PEGAWAI_ID, KODE, HALAMAN, INFORMASI, KODE_HALAMAN FROM
				(
				SELECT A.PEGAWAI_ID, A.NAMA KODE, HALAMAN, '' INFORMASI, A.KODE KODE_HALAMAN
				FROM CHAT A INNER JOIN PAKET_REKANAN B ON A.PEGAWAI_ID = B.PEGAWAI_ID AND A.NAMA = B.KODE_REKANAN WHERE 1 = 1 AND PESAN = 'CONFIRMED' AND A.NAMA = '" . $rekanan_kode . "'
				UNION ALL
                SELECT PEGAWAI_ID, 'PESAN' KODE, HALAMAN, INFORMASI, A.KODE KODE_HALAMAN
				FROM
				(
                SELECT A.PEGAWAI_ID, HALAMAN, A.KODE, COUNT(1), (SELECT COUNT(1) JUMLAH_REKANAN
								FROM CHAT X INNER JOIN PAKET_REKANAN Y ON X.PEGAWAI_ID = Y.PEGAWAI_ID AND X.NAMA = Y.KODE_REKANAN WHERE 1=1 AND NOT PESAN = 'CONFIRMED' AND X.PEGAWAI_ID = A.PEGAWAI_ID AND X.HALAMAN = A.HALAMAN AND X.KODE = A.KODE AND X.NAMA = '" . $rekanan_kode . "' AND NOT JAM IS NULL)
                || '/' ||
                (SELECT COUNT(1) JUMLAH_REKANAN
                                FROM CHAT X LEFT JOIN PAKET_REKANAN Y ON X.PEGAWAI_ID = Y.PEGAWAI_ID AND X.NAMA = Y.KODE_REKANAN WHERE 1=1 AND NOT PESAN = 'CONFIRMED' AND PEGAWAI_ID IS NULL AND X.PEGAWAI_ID = A.PEGAWAI_ID AND X.HALAMAN = A.HALAMAN AND X.KODE = A.KODE AND NOT JAM IS NULL)
                INFORMASI
                FROM CHAT A INNER JOIN PAKET_REKANAN B ON A.PEGAWAI_ID = B.PEGAWAI_ID AND A.NAMA = B.KODE_REKANAN WHERE 1 = 1 AND NOT PESAN = 'CONFIRMED' AND NOT JAM IS NULL
                GROUP BY A.PEGAWAI_ID, HALAMAN, A.KODE
                ) A
                ) A WHERE 1 = 1
		";

		//JAM IS NOT NULL
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}
		$str .= " " . $order;
		//echo $str;
		$this->query = $str;
		return $this->selectLimit($str, $limit, $from);
	}

	function selectByParamsLike($paramsArray = array(), $limit = -1, $from = -1, $statement = '')
	{
		$str = "SELECT JAM, NAMA
				FROM CHAT WHERE JAM IS NOT NULL";

		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key LIKE '%$val%' ";
		}

		$this->query = $str;
		$str .= $statement . " ORDER BY NAMA ASC";
		return $this->selectLimit($str, $limit, $from);
	}

	/**
	 * Hitung jumlah record berdasarkan parameter (array).
	 * @param array paramsArray Array of parameter. Contoh array("id"=>"xxx","NAMA"=>"yyy")
	 * @return long Jumlah record yang sesuai kriteria
	 **/

	function getCountByParamsTerakhir($paramsArray = array())
	{
		$str = "SELECT COUNT(1) AS ROWCOUNT  FROM CHAT_TERAKHIR A
                    WHERE 1=1  ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function getCountByParams($paramsArray = array())
	{
		$str = "SELECT COUNT(JAM) AS ROWCOUNT FROM CHAT WHERE JAM IS NOT NULL ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key = '$val' ";
		}

		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}

	function getWaktu($paramsArray = array())
	{
		$str = "SELECT TO_CHAR(CURRENT_TIMESTAMP, 'DD-MM-YYYY HH24:MI:SS') AS ROWCOUNT ";

		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return "";
	}



	function getPesanMasuk($paket_id, $halaman, $kode)
	{
		$str = "
				SELECT A.JUMLAH_REKANAN || '/' || B.JUMLAH_PANITIA PESAN FROM
				(
				SELECT COUNT(1) JUMLAH_REKANAN
								FROM CHAT A INNER JOIN REKANAN B ON A.NAMA = B.KODE WHERE 1=1 AND PEGAWAI_ID = " . $paket_id . " AND HALAMAN = " . $halaman . " AND A.KODE = '" . $kode . "' AND NOT PESAN = 'CONFIRMED'
				) A,
				(
				SELECT COUNT(1) JUMLAH_PANITIA
								FROM CHAT A LEFT JOIN REKANAN B ON A.NAMA = B.KODE WHERE 1=1 AND PEGAWAI_ID = " . $paket_id . " AND HALAMAN = " . $halaman . " AND A.KODE = '" . $kode . "' AND PEGAWAI_ID IS NULL AND NOT PESAN = 'CONFIRMED'
				) B
		 ";
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("PESAN");
		else
			return "0/0";
	}

	function getPesanMasukRekanan($paket_id, $halaman, $kode, $rekanan_kode)
	{
		$str = "
				SELECT A.JUMLAH_REKANAN || '/' || B.JUMLAH_PANITIA PESAN FROM
				(
				SELECT COUNT(1) JUMLAH_REKANAN
								FROM CHAT A INNER JOIN REKANAN B ON A.NAMA = B.KODE WHERE 1=1 AND PEGAWAI_ID = " . $paket_id . " AND HALAMAN = " . $halaman . " AND A.KODE = '" . $kode . "' AND A.NAMA = '" . $rekanan_kode . "' AND NOT PESAN = 'CONFIRMED'
				) A,
				(
				SELECT COUNT(1) JUMLAH_PANITIA
								FROM CHAT A LEFT JOIN REKANAN B ON A.NAMA = B.KODE WHERE 1=1 AND PEGAWAI_ID = " . $paket_id . " AND HALAMAN = " . $halaman . " AND A.KODE = '" . $kode . "' AND PEGAWAI_ID IS NULL AND NOT PESAN = 'CONFIRMED'
				) B
		 ";
		$this->select($str);
		if ($this->firstRow())
			return $this->getField("PESAN");
		else
			return "0/0";
	}

	function getCountByParamsLike($paramsArray = array())
	{
		$str = "SELECT COUNT(JAM) AS ROWCOUNT FROM CHAT WHERE JAM IS NOT NULL ";
		while (list($key, $val) = each($paramsArray)) {
			$str .= " AND $key LIKE '%$val%' ";
		}

		$this->select($str);
		if ($this->firstRow())
			return $this->getField("ROWCOUNT");
		else
			return 0;
	}
}
