<?php namespace tests\Foothing\Laravel\Consent;

use Foothing\Laravel\Consent\ConsentApi;
use Foothing\Laravel\Consent\Facades\Consent;
use Foothing\Laravel\Consent\Models\Treatment;
use Foothing\Laravel\Consent\Repositories\ConsentRepository;
use Mockery\MockInterface;
use tests\Foothing\Laravel\Consent\Mocks\User;

class ConsentApiTest extends DatabaseTest {

    /**
     * @var MockInterface
     */
    protected $repository;

    /**
     * @var ConsentApi
     */
    protected $api;

    public function setUp()
    {
        parent::setUp();

        $this->repository = \Mockery::mock(ConsentRepository::class);

        $this->api = new ConsentApi($this->repository);
    }

    /**
     * One consent and one event is created for each treatment.
     *
     * @test
     */
    public function it_should_create_new_consent_and_log()
    {
        $treatments = Treatment::all();

        $consents = $this->api->grant($treatments->all(), $user = new User());

        $this->assertEquals($treatments->count(), count($consents));
        $this->assertEquals($treatments->count(), $this->api->events($user)->count());
    }

    /**
     * One consent and one event is created for each treatment.
     *
     * @test
     */
    public function it_should_revoke_and_log()
    {
        $treatments = Treatment::all();

        $consents = $this->api->grant($treatments->all(), $user = new User());
    }

    /**
     * @test
     */
    //public function it_should_return_empty_array_if_treatments_is_empty()
    //{
    //    $this->assertEquals([], $this->api->grant([], new User()));
    //}

    /**
     * @test
     */
//    public function it_should_create_a_new_entry_if_no_record_is_found()
//    {
//        $this
//            ->repository
//            ->shouldReceive('findConsentBySubjectAndTreatment')
//            ->andReturn(null);
//
//        $this->api->grant([], new User());
//    }
}
