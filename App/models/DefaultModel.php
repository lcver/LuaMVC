<?php
/**
 * Initialized Class
 * @return Init
 */
use App\Core\Controller;

class DefaultModel extends Controller
{
    /**
     * Nama
     * @var String
     */
    protected $nama;

    /**
     * Umur
     * @var Integer
     */
    protected $umur;

    /**
     * Asal Sekolah
     * @var String
     */
    protected $sekolah;

    /**
     * Data
     * To return
     * @var Object
     */
    public $data;

    /**
     * Set Data
     * Catch data params
     * 
     * @param String
     * Nama, Sekolah
     * @param Integer
     * Umur
     */
    public function set_data($nama, $umur, $sekolah)
    {
        $this->nama = $nama;
        $this->umur = $umur;
        $this->sekolah = $sekolah;
    }

    /**
     * Get Data
     * Return data object
     * 
     * @return object
     */
    public function get_data()
    {       
        return $this;
    }
    
}
