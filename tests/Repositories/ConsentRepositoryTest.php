<?php namespace tests\Foothing\Laravel\Consent\Repositories;

use Foothing\Laravel\Consent\Models\Consent;
use Foothing\Laravel\Consent\Repositories\ConsentRepository;
use tests\Foothing\Laravel\Consent\DatabaseTest;

class ConsentRepositoryTest extends DatabaseTest {

    /**
     * @var ConsentRepository
     */
    protected $repository;

    public function setUp() {
        parent::setUp();
        $this->repository = new ConsentRepository(new Consent());
    }

    /**
     * @test
     */
    public function it_should_retrieve_consent() {
        //$this->assertNotNull($this->repository->findConsentBySubjectAndTreatment(1, 1));
    }

}
