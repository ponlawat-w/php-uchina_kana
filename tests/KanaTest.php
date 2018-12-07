<?php
class KanaTest extends PHPUnit\Framework\TestCase
{
    public function testGenerate() {
        $this->assertEquals('あ', kana('', 'a'));
        $this->assertEquals('く', kana('k', 'u'));
        $this->assertEquals('ぐゎ', kana('gw', 'a'));
        $this->assertEquals('しぇ', kana('s', 'e'));
        $this->assertEquals('や', kana('j', 'a'));
        $this->assertEquals('ん', kana('n', ''));
        $this->assertEquals('っ', kana('q', ''));
        $this->assertEquals('や', kana('y', 'a'));
        $this->assertEquals('ゐ', kana('j', 'i'));
        $this->assertEquals('ちゅ', kana('c', 'u'));
        $this->assertEquals('じゃ', kana('z', 'a'));
        $this->assertEquals('っく', kana('qk', 'u'));
        $this->assertEquals('ってぃ', kana('qt', 'i'));
    }
    
    public function testImplode() {
        $this->assertEquals('かちゃーしー', kana_implode('か', 'ちゃ', 'あ', 'し', 'い'));
        $this->assertEquals('うちなーぐち', kana_implode('う', 'ち', 'な', 'あ', 'ぐ', 'ち'));
        $this->assertEquals('ちゃっさ', kana_implode('ちゃ', 'っ', 'さ'));
        $this->assertEquals('ちゃっさ', kana_implode('ちゃ', 'っさ'));
        $this->assertEquals('やっさ', kana_implode('や', 'っ', 'さ'));
        $this->assertEquals('やっさ', kana_implode('や', 'っさ'));
    }

    public function testSplit() {
        $this->assertEquals(['や', 'ま', 'とぅ'], kana_explode('やまとぅ'));
        $this->assertEquals(['ん', 'な'], kana_explode('んな'));
        $this->assertEquals(['や', 'っ', 'さ'], kana_explode('やっさ'));
        $this->assertEquals(['か', 'ちゃ', 'あ', 'し', 'い'], kana_explode('かちゃーしー'));
        $this->assertEquals(['う', 'ち', 'な', 'あ', 'ぐ', 'ち'], kana_explode('うちなーぐち'));
        $this->assertEquals(['ちゃ', 'っ', 'さ'], kana_explode('ちゃっさ'));
    }

    public function testReformat() {
        $this->assertEquals('やまとぅぐち', kana_reformat('やまとぅぐち'));
        $this->assertEquals('うちなーぐち', kana_reformat('うちなあぐち'));
        $this->assertEquals('かちゃーしー', kana_reformat('かちゃーしー'));
        $this->assertEquals('かちゃーしー', kana_reformat('かちゃあしい'));
        $this->assertEquals('かちゃーしー', kana_reformat('かちゃあしー'));
    }

    public function testFromRomaji() {
        $this->assertEquals('あーばーさーばー', kana_from_romaji('?aabaasaabaa'));
        $this->assertEquals('んなむじ', kana_from_romaji('?Nnamuzi'));
        $this->assertEquals('うやむどぅいんぐゎ', kana_from_romaji('?ujamuduiN]gwa'));
        $this->assertEquals('うやちらさ', kana_from_romaji('?ujaCirasa'));
        $this->assertEquals('っくゎちくしょー', kana_from_romaji('Qkwacikusjoo'));
        $this->assertEquals('ふぃっとぅゆん', kana_from_romaji('hwiQtu=juN'));
        $this->assertEquals('ふぃっとぅゆん', kana_from_romaji('hwittu=juN'));
        $this->assertEquals('みゅんちぐとぅ', kana_from_romaji('mjuNcigutu'));
        $this->assertEquals('むちゅん', kana_from_romaji('mu=cuN'));
        $this->assertEquals('ひーいー', kana_from_romaji('hii?ii'));
        $this->assertEquals('はじゅん', kana_from_romaji('ha=zuN'));
        $this->assertEquals('みされーぱーぱー', kana_from_romaji('misareepaa]paa'));
        $this->assertEquals('さんめーなーび', kana_from_romaji('saNmeenaabi'));
        $this->assertEquals('ししいりち', kana_from_romaji('sisi?irici'));
        $this->assertEquals('ししくぇーぼーじ', kana_from_romaji('sisikweeboozi'));
        $this->assertEquals('ししむちない', kana_from_romaji('sisimucinai'));
        $this->assertEquals('ししぬん', kana_from_romaji('SiSi=nuN'));
        $this->assertEquals('うんぬきゆん', kana_from_romaji('?uNnuki=ju]N'));
        $this->assertEquals('うんぱだん', kana_from_romaji('?uNpada]N'));
        $this->assertEquals('うっちゃんぎゆん', kana_from_romaji('?uQcaNgi=ju]N'));
        $this->assertEquals('うっちゃんぎゆん', kana_from_romaji('?uccaNgi=ju]N'));
        $this->assertEquals('ゆちあじまー', kana_from_romaji('juCi?azimaa'));
    }

    public function testFromKana() {
        $this->assertEquals('mu', romaji_from_kana('む'));
        $this->assertEquals('sja', romaji_from_kana('しゃ'));
        $this->assertEquals('n', romaji_from_kana('ん'));
        $this->assertEquals('q', romaji_from_kana('っ'));
        $this->assertEquals('zi', romaji_from_kana('じ'));
        $this->assertEquals('gwa', romaji_from_kana('ぐゎ'));
    }

    public function testFromKanas() {
        $this->assertEquals('nahwa', romaji_from_kanas('なふぁ'));
        $this->assertEquals('umuin', romaji_from_kanas('うむいん'));
        $this->assertEquals('tuqtin', romaji_from_kanas('とぅってぃん'));
        $this->assertEquals('zintojoo', romaji_from_kanas('じんとよー'));
        $this->assertEquals('uuzi', romaji_from_kanas('うーじ'));
        $this->assertEquals('tinsagunuhana', romaji_from_kanas('てぃんさぐぬはな'));
        $this->assertEquals('tinsjaaguu', romaji_from_kanas('てぃんしゃーぐー'));
    }
}
