<?php

namespace DoctrineModuleTest\Stdlib\Hydrator;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineObjectHydrator;
use DoctrineModule\Stdlib\Hydrator\Filter;
use DoctrineModule\Stdlib\Hydrator\Strategy;
use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;

class DoctrineObjectTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var DoctrineObjectHydrator
     */
    protected $hydratorByValue;

    /**
     * @var DoctrineObjectHydrator
     */
    protected $hydratorByReference;

    /**
     * @var ClassMetadata|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $metadata;

    /**
     * @var ObjectManager|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $objectManager;

    /**
     * setUp
     */
    public function setUp()
    {
        parent::setUp();

        $this->metadata      = $this->createMock(ClassMetadata::class);
        $this->objectManager = $this->createMock(ObjectManager::class);

        $this->objectManager->expects($this->any())
                            ->method('getClassMetadata')
                            ->will($this->returnValue($this->metadata));
    }

    public function configureObjectManagerForSimpleEntity()
    {
        $refl = new \ReflectionClass(Asset\SimpleEntity::class);

        $this
            ->metadata
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(Asset\SimpleEntity::class));
        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationNames')
            ->will($this->returnValue([]));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getFieldNames')
            ->will($this->returnValue(['id', 'field']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getTypeOfField')
            ->with($this->logicalOr($this->equalTo('id'), $this->equalTo('field')))
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        if ('id' === $arg) {
                            return 'integer';
                        } elseif ('field' === $arg) {
                            return 'string';
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $this
            ->metadata
            ->expects($this->any())
            ->method('hasAssociation')
            ->will($this->returnValue(false));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getIdentifierFieldNames')
            ->will($this->returnValue(['id']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getReflectionClass')
            ->will($this->returnValue($refl));

        $this->hydratorByValue     = new DoctrineObjectHydrator(
            $this->objectManager,
            true
        );
        $this->hydratorByReference = new DoctrineObjectHydrator(
            $this->objectManager,
            false
        );
    }

    public function configureObjectManagerForNamingStrategyEntity()
    {
        $refl = new \ReflectionClass(Asset\NamingStrategyEntity::class);

        $this
            ->metadata
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(Asset\NamingStrategyEntity::class));
        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationNames')
            ->will($this->returnValue([]));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getFieldNames')
            ->will($this->returnValue(['camelCase']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getTypeOfField')
            ->with($this->equalTo('camelCase'))
            ->will($this->returnValue('string'));

        $this
            ->metadata
            ->expects($this->any())
            ->method('hasAssociation')
            ->will($this->returnValue(false));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getIdentifierFieldNames')
            ->will($this->returnValue(['camelCase']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getReflectionClass')
            ->will($this->returnValue($refl));

        $this->hydratorByValue     = new DoctrineObjectHydrator(
            $this->objectManager,
            true
        );
        $this->hydratorByReference = new DoctrineObjectHydrator(
            $this->objectManager,
            false
        );
    }

    public function configureObjectManagerForSimpleIsEntity()
    {
        $refl = new \ReflectionClass(Asset\SimpleIsEntity::class);

        $this
            ->metadata
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(Asset\SimpleIsEntity::class));
        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationNames')
            ->will($this->returnValue([]));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getFieldNames')
            ->will($this->returnValue(['id', 'done']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getTypeOfField')
            ->with($this->logicalOr($this->equalTo('id'), $this->equalTo('done')))
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        if ('id' === $arg) {
                            return 'integer';
                        } elseif ('done' === $arg) {
                            return 'boolean';
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $this
            ->metadata
            ->expects($this->any())
            ->method('hasAssociation')
            ->will($this->returnValue(false));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getIdentifierFieldNames')
            ->will($this->returnValue(['id']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getReflectionClass')
            ->will($this->returnValue($refl));

        $this->hydratorByValue     = new DoctrineObjectHydrator(
            $this->objectManager,
            true
        );
        $this->hydratorByReference = new DoctrineObjectHydrator(
            $this->objectManager,
            false
        );
    }

    public function configureObjectManagerForSimpleEntityWithIsBoolean()
    {
        $refl = new \ReflectionClass(Asset\SimpleEntityWithIsBoolean::class);

        $this
            ->metadata
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(Asset\SimpleEntityWithIsBoolean::class));
        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationNames')
            ->will($this->returnValue([]));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getFieldNames')
            ->will($this->returnValue(['id', 'isActive']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getTypeOfField')
            ->with($this->logicalOr($this->equalTo('id'), $this->equalTo('isActive')))
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        if ('id' === $arg) {
                            return 'integer';
                        } elseif ('isActive' === $arg) {
                            return 'boolean';
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $this
            ->metadata
            ->expects($this->any())
            ->method('hasAssociation')
            ->will($this->returnValue(false));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getIdentifierFieldNames')
            ->will($this->returnValue(['id']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getReflectionClass')
            ->will($this->returnValue($refl));

        $this->hydratorByValue     = new DoctrineObjectHydrator(
            $this->objectManager,
            true
        );
        $this->hydratorByReference = new DoctrineObjectHydrator(
            $this->objectManager,
            false
        );
    }

    public function configureObjectManagerForSimpleEntityWithStringId()
    {
        $refl = new \ReflectionClass(Asset\SimpleEntity::class);

        $this
            ->metadata
            ->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(Asset\SimpleEntity::class));
        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationNames')
            ->will($this->returnValue([]));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getFieldNames')
            ->will($this->returnValue(['id', 'field']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getTypeOfField')
            ->with($this->logicalOr($this->equalTo('id'), $this->equalTo('field')))
            ->will($this->returnValue('string'));

        $this
            ->metadata
            ->expects($this->any())
            ->method('hasAssociation')
            ->will($this->returnValue(false));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getIdentifierFieldNames')
            ->will($this->returnValue(['id']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getReflectionClass')
            ->will($this->returnValue($refl));

        $this->hydratorByValue     = new DoctrineObjectHydrator(
            $this->objectManager,
            true
        );
        $this->hydratorByReference = new DoctrineObjectHydrator(
            $this->objectManager,
            false
        );
    }

    public function configureObjectManagerForSimpleEntityWithDateTime()
    {
        $refl = new \ReflectionClass(Asset\SimpleEntityWithDateTime::class);

        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationNames')
            ->will($this->returnValue([]));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getFieldNames')
            ->will($this->returnValue(['id', 'date']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getTypeOfField')
            ->with($this->logicalOr($this->equalTo('id'), $this->equalTo('date')))
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        if ($arg === 'id') {
                            return 'integer';
                        } elseif ($arg === 'date') {
                            return 'datetime';
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $this
            ->metadata
            ->expects($this->any())
            ->method('hasAssociation')
            ->will($this->returnValue(false));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getIdentifierFieldNames')
            ->will($this->returnValue(['id']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getReflectionClass')
            ->will($this->returnValue($refl));

        $this->hydratorByValue     = new DoctrineObjectHydrator(
            $this->objectManager,
            true
        );
        $this->hydratorByReference = new DoctrineObjectHydrator(
            $this->objectManager,
            false
        );
    }

    public function configureObjectManagerForOneToOneEntity()
    {
        $refl = new \ReflectionClass(Asset\OneToOneEntity::class);

        $this
            ->metadata
            ->expects($this->any())
            ->method('getFieldNames')
            ->will($this->returnValue(['id']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationNames')
            ->will($this->returnValue(['toOne']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getTypeOfField')
            ->with($this->logicalOr($this->equalTo('id'), $this->equalTo('toOne')))
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        if ($arg === 'id') {
                            return 'integer';
                        } elseif ($arg === 'toOne') {
                            return Asset\SimpleEntity::class;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $this
            ->metadata
            ->expects($this->any())
            ->method('hasAssociation')
            ->with($this->logicalOr($this->equalTo('id'), $this->equalTo('toOne')))
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        if ($arg === 'id') {
                            return false;
                        } elseif ($arg === 'toOne') {
                            return true;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $this
            ->metadata
            ->expects($this->any())
            ->method('isSingleValuedAssociation')
            ->with('toOne')
            ->will($this->returnValue(true));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationTargetClass')
            ->with('toOne')
            ->will($this->returnValue(Asset\SimpleEntity::class));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getReflectionClass')
            ->will($this->returnValue($refl));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue(["id"]));

        $this->hydratorByValue     = new DoctrineObjectHydrator(
            $this->objectManager,
            true
        );
        $this->hydratorByReference = new DoctrineObjectHydrator(
            $this->objectManager,
            false
        );
    }

    public function configureObjectManagerForOneToOneEntityNotNullable()
    {
        $refl = new \ReflectionClass(Asset\OneToOneEntityNotNullable::class);

        $this
            ->metadata
            ->expects($this->any())
            ->method('getFieldNames')
            ->will($this->returnValue(['id']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationNames')
            ->will($this->returnValue(['toOne']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getTypeOfField')
            ->with(
                $this->logicalOr(
                    $this->equalTo('id'),
                    $this->equalTo('toOne'),
                    $this->equalTo('field')
                )
            )
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        if ($arg === 'id') {
                            return 'integer';
                        } elseif ($arg === 'toOne') {
                            return Asset\SimpleEntity::class;
                        } elseif ($arg === 'field') {
                            return 'string';
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $this
            ->metadata
            ->expects($this->any())
            ->method('hasAssociation')
            ->with(
                $this->logicalOr(
                    $this->equalTo('id'),
                    $this->equalTo('toOne'),
                    $this->equalTo('field')
                )
            )
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        if ($arg === 'id' || $arg === 'field') {
                            return false;
                        } elseif ($arg === 'toOne') {
                            return true;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $this
            ->metadata
            ->expects($this->any())
            ->method('isSingleValuedAssociation')
            ->with('toOne')
            ->will($this->returnValue(true));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationTargetClass')
            ->with('toOne')
            ->will($this->returnValue(Asset\SimpleEntity::class));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getReflectionClass')
            ->will($this->returnValue($refl));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue(["id"]));

        $this->hydratorByValue     = new DoctrineObjectHydrator(
            $this->objectManager,
            true
        );
        $this->hydratorByReference = new DoctrineObjectHydrator(
            $this->objectManager,
            false
        );
    }

    public function configureObjectManagerForOneToManyEntity()
    {
        $refl = new \ReflectionClass(Asset\OneToManyEntity::class);

        $this
            ->metadata
            ->expects($this->any())
            ->method('getFieldNames')
            ->will($this->returnValue(['id']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationNames')
            ->will($this->returnValue(['entities']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getTypeOfField')
            ->with(
                $this->logicalOr(
                    $this->equalTo('id'),
                    $this->equalTo('entities'),
                    $this->equalTo('field')
                )
            )
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        if ($arg === 'id') {
                            return 'integer';
                        } elseif ($arg === 'field') {
                            return 'string';
                        } elseif ($arg === 'entities') {
                            return ArrayCollection::class;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $this
            ->metadata
            ->expects($this->any())
            ->method('hasAssociation')
            ->with($this->logicalOr($this->equalTo('id'), $this->equalTo('entities'), $this->equalTo('field')))
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        if ($arg === 'id') {
                            return false;
                        } elseif ($arg === 'field') {
                            return false;
                        } elseif ($arg === 'entities') {
                            return true;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $this
            ->metadata
            ->expects($this->any())
            ->method('isSingleValuedAssociation')
            ->with('entities')
            ->will($this->returnValue(false));

        $this
            ->metadata
            ->expects($this->any())
            ->method('isCollectionValuedAssociation')
            ->with('entities')
            ->will($this->returnValue(true));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationTargetClass')
            ->with('entities')
            ->will($this->returnValue(Asset\SimpleEntity::class));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getReflectionClass')
            ->will($this->returnValue($refl));

        $this->metadata
            ->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue(["id"]));

        $this->hydratorByValue     = new DoctrineObjectHydrator(
            $this->objectManager,
            true
        );
        $this->hydratorByReference = new DoctrineObjectHydrator(
            $this->objectManager,
            false
        );

    }

    public function configureObjectManagerForOneToManyArrayEntity()
    {
        $refl = new \ReflectionClass(Asset\OneToManyArrayEntity::class);

        $this
            ->metadata
            ->expects($this->any())
            ->method('getFieldNames')
            ->will($this->returnValue(['id']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationNames')
            ->will($this->returnValue(['entities']));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getTypeOfField')
            ->with(
                $this->logicalOr(
                    $this->equalTo('id'),
                    $this->equalTo('entities'),
                    $this->equalTo('field')
                )
            )
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        if ($arg === 'id') {
                            return 'integer';
                        } elseif ($arg === 'field') {
                            return 'string';
                        } elseif ($arg === 'entities') {
                            return ArrayCollection::class;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $this
            ->metadata
            ->expects($this->any())
            ->method('hasAssociation')
            ->with($this->logicalOr($this->equalTo('id'), $this->equalTo('entities')))
            ->will(
                $this->returnCallback(
                    function ($arg) {
                        if ($arg === 'id') {
                            return false;
                        } elseif ($arg === 'field') {
                            return 'string';
                        } elseif ($arg === 'entities') {
                            return true;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $this
            ->metadata
            ->expects($this->any())
            ->method('isSingleValuedAssociation')
            ->with('entities')
            ->will($this->returnValue(false));

        $this
            ->metadata
            ->expects($this->any())
            ->method('isCollectionValuedAssociation')
            ->with('entities')
            ->will($this->returnValue(true));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getAssociationTargetClass')
            ->with('entities')
            ->will($this->returnValue(Asset\SimpleEntity::class));

        $this
            ->metadata
            ->expects($this->any())
            ->method('getReflectionClass')
            ->will($this->returnValue($refl));

        $this->metadata
            ->expects($this->any())
            ->method('getIdentifier')
            ->will($this->returnValue(["id"]));

        $this->hydratorByValue     = new DoctrineObjectHydrator(
            $this->objectManager,
            true
        );
        $this->hydratorByReference = new DoctrineObjectHydrator(
            $this->objectManager,
            false
        );
    }

    public function testObjectIsPassedForContextToStrategies()
    {
        $entity = new Asset\ContextEntity();
        $entity->setId(2);
        $entity->setField('foo');

        $this->configureObjectManagerForSimpleEntity();

        $hydrator = $this->hydratorByValue;
        $entity   = $hydrator->hydrate(['id' => 3, 'field' => 'bar'], $entity);
        $this->assertEquals(['id' => 3, 'field' => 'bar'], $hydrator->extract($entity));

        $hydrator->addStrategy('id', new Asset\ContextStrategy());
        $entity = $hydrator->hydrate(['id' => 3, 'field' => 'bar'], $entity);
        $this->assertEquals(['id' => '3barbar', 'field' => 'bar'], $hydrator->extract($entity));
    }

    public function testCanExtractSimpleEntityByValue()
    {
        // When using extraction by value, it will use the public API of the entity to retrieve values (getters)
        $entity = new Asset\SimpleEntity();
        $entity->setId(2);
        $entity->setField('foo', false);

        $this->configureObjectManagerForSimpleEntity();

        $data = $this->hydratorByValue->extract($entity);
        $this->assertEquals(['id' => 2, 'field' => 'From getter: foo'], $data);
    }

    public function testCanExtractSimpleEntityByReference()
    {
        // When using extraction by reference, it won't use the public API of entity (getters won't be called)
        $entity = new Asset\SimpleEntity();
        $entity->setId(2);
        $entity->setField('foo', false);

        $this->configureObjectManagerForSimpleEntity();

        $data = $this->hydratorByReference->extract($entity);
        $this->assertEquals(['id' => 2, 'field' => 'foo'], $data);
    }

    public function testCanHydrateSimpleEntityByValue()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $entity = new Asset\SimpleEntity();
        $this->configureObjectManagerForSimpleEntity();
        $data = ['field' => 'foo'];

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity);
        $this->assertEquals('From setter: foo', $entity->getField(false));
    }

    /**
     * When using hydration by value, it will use the public API of the entity to set values (setters)
     *
     * @covers \DoctrineModule\Stdlib\Hydrator\DoctrineObject::hydrateByValue
     */
    public function testCanHydrateSimpleEntityWithStringIdByValue()
    {
        $entity = new Asset\SimpleEntity();
        $data   = ['id' => 'bar', 'field' => 'foo'];

        $this->configureObjectManagerForSimpleEntityWithStringId();

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity);
        $this->assertEquals('From setter: foo', $entity->getField(false));
    }

    public function testCanHydrateSimpleEntityByReference()
    {
        // When using hydration by reference, it won't use the public API of the entity to set values (setters)
        $entity = new Asset\SimpleEntity();
        $this->configureObjectManagerForSimpleEntity();
        $data = ['field' => 'foo'];

        $entity = $this->hydratorByReference->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity);
        $this->assertEquals('foo', $entity->getField(false));
    }

    /**
     * When using hydration by reference, it won't use the public API of the entity to set values (getters)
     *
     * @covers \DoctrineModule\Stdlib\Hydrator\DoctrineObject::hydrateByReference
     */
    public function testCanHydrateSimpleEntityWithStringIdByReference()
    {
        $entity = new Asset\SimpleEntity();
        $data   = ['id' => 'bar', 'field' => 'foo'];

        $this->configureObjectManagerForSimpleEntityWithStringId();

        $entity = $this->hydratorByReference->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity);
        $this->assertEquals('foo', $entity->getField(false));
    }

    public function testReuseExistingEntityIfDataArrayContainsIdentifier()
    {
        // When using hydration by reference, it won't use the public API of the entity to set values (setters)
        $entity = new Asset\SimpleEntity();

        $this->configureObjectManagerForSimpleEntity();
        $data = ['id' => 1];

        $entityInDatabaseWithIdOfOne = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfOne->setId(1);
        $entityInDatabaseWithIdOfOne->setField('bar', false);

        $this
            ->objectManager
            ->expects($this->once())
            ->method('find')
            ->with(Asset\SimpleEntity::class, ['id' => 1])
            ->will($this->returnValue($entityInDatabaseWithIdOfOne));

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity);
        $this->assertEquals('bar', $entity->getField(false));
    }

    /**
     * Test for https://github.com/doctrine/DoctrineModule/issues/456
     */
    public function testReuseExistingEntityIfDataArrayContainsIdentifierWithZeroIdentifier()
    {
        // When using hydration by reference, it won't use the public API of the entity to set values (setters)
        $entity = new Asset\SimpleEntity();

        $this->configureObjectManagerForSimpleEntity();
        $data = ['id' => 0];

        $entityInDatabaseWithIdOfOne = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfOne->setId(0);
        $entityInDatabaseWithIdOfOne->setField('bar', false);

        $this
            ->objectManager
            ->expects($this->once())
            ->method('find')
            ->with(Asset\SimpleEntity::class, ['id' => 0])
            ->will($this->returnValue($entityInDatabaseWithIdOfOne));

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity);
        $this->assertEquals('bar', $entity->getField(false));
    }

    public function testExtractOneToOneAssociationByValue()
    {
        // When using extraction by value, it will use the public API of the entity to retrieve values (getters)
        $toOne = new Asset\SimpleEntity();
        $toOne->setId(2);
        $toOne->setField('foo', false);

        $entity = new Asset\OneToOneEntity();
        $entity->setId(2);
        $entity->setToOne($toOne);

        $this->configureObjectManagerForOneToOneEntity();

        $data = $this->hydratorByValue->extract($entity);

        $this->assertEquals(2, $data['id']);
        $this->assertInstanceOf(Asset\SimpleEntity::class, $data['toOne']);
        $this->assertEquals('Modified from getToOne getter', $data['toOne']->getField(false));
        $this->assertSame($toOne, $data['toOne']);
    }

    public function testExtractOneToOneAssociationByReference()
    {
        // When using extraction by value, it will use the public API of the entity to retrieve values (getters)
        $toOne = new Asset\SimpleEntity();
        $toOne->setId(2);
        $toOne->setField('foo', false);

        $entity = new Asset\OneToOneEntity();
        $entity->setId(2);
        $entity->setToOne($toOne, false);

        $this->configureObjectManagerForOneToOneEntity();

        $data = $this->hydratorByReference->extract($entity);

        $this->assertEquals(2, $data['id']);
        $this->assertInstanceOf(Asset\SimpleEntity::class, $data['toOne']);
        $this->assertEquals('foo', $data['toOne']->getField(false));
        $this->assertSame($toOne, $data['toOne']);
    }

    public function testHydrateOneToOneAssociationByValue()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $toOne = new Asset\SimpleEntity();
        $toOne->setId(2);
        $toOne->setField('foo', false);

        $entity = new Asset\OneToOneEntity();
        $this->configureObjectManagerForOneToOneEntity();

        $data = ['toOne' => $toOne];

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToOneEntity::class, $entity);
        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity->getToOne(false));
        $this->assertEquals('Modified from setToOne setter', $entity->getToOne(false)->getField(false));
    }

    public function testHydrateOneToOneAssociationByReference()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $toOne = new Asset\SimpleEntity();
        $toOne->setId(2);
        $toOne->setField('foo', false);

        $entity = new Asset\OneToOneEntity();
        $this->configureObjectManagerForOneToOneEntity();

        $data = ['toOne' => $toOne];

        $entity = $this->hydratorByReference->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToOneEntity::class, $entity);
        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity->getToOne(false));
        $this->assertEquals('foo', $entity->getToOne(false)->getField(false));
    }

    public function testHydrateOneToOneAssociationByValueUsingIdentifierForRelation()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $entity = new Asset\OneToOneEntity();
        $this->configureObjectManagerForOneToOneEntity();

        // Use entity of id 1 as relation
        $data = ['toOne' => 1];

        $entityInDatabaseWithIdOfOne = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfOne->setId(1);
        $entityInDatabaseWithIdOfOne->setField('bar', false);

        $this
            ->objectManager
            ->expects($this->once())
            ->method('find')
            ->with(Asset\SimpleEntity::class, 1)
            ->will($this->returnValue($entityInDatabaseWithIdOfOne));

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToOneEntity::class, $entity);
        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity->getToOne(false));
        $this->assertSame($entityInDatabaseWithIdOfOne, $entity->getToOne(false));
    }

    public function testHydrateOneToOneAssociationByReferenceUsingIdentifierForRelation()
    {
        // When using hydration by reference, it won't use the public API of the entity to set values (setters)
        $entity = new Asset\OneToOneEntity();
        $this->configureObjectManagerForOneToOneEntity();

        // Use entity of id 1 as relation
        $data = ['toOne' => 1];

        $entityInDatabaseWithIdOfOne = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfOne->setId(1);
        $entityInDatabaseWithIdOfOne->setField('bar', false);

        $this
            ->objectManager
            ->expects($this->once())
            ->method('find')
            ->with(Asset\SimpleEntity::class, 1)
            ->will($this->returnValue($entityInDatabaseWithIdOfOne));

        $entity = $this->hydratorByReference->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToOneEntity::class, $entity);
        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity->getToOne(false));
        $this->assertSame($entityInDatabaseWithIdOfOne, $entity->getToOne(false));
    }

    public function testHydrateOneToOneAssociationByValueUsingIdentifierArrayForRelation()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $entity = new Asset\OneToOneEntity();
        $this->configureObjectManagerForOneToOneEntity();

        // Use entity of id 1 as relation
        $data = ['toOne' => ['id' => 1]];

        $entityInDatabaseWithIdOfOne = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfOne->setId(1);
        $entityInDatabaseWithIdOfOne->setField('bar', false);

        $this
            ->objectManager
            ->expects($this->once())
            ->method('find')
            ->with(Asset\SimpleEntity::class, ['id' => 1])
            ->will($this->returnValue($entityInDatabaseWithIdOfOne));

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToOneEntity::class, $entity);
        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity->getToOne(false));
        $this->assertSame($entityInDatabaseWithIdOfOne, $entity->getToOne(false));
    }

    public function testHydrateOneToOneAssociationByValueUsingFullArrayForRelation()
    {
        $entity = new Asset\OneToOneEntityNotNullable;
        $this->configureObjectManagerForOneToOneEntityNotNullable();

        // Use entity of id 1 as relation
        $data = ['toOne' => ['id' => 1, 'field' => 'foo']];

        $entityInDatabaseWithIdOfOne = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfOne->setId(1);
        $entityInDatabaseWithIdOfOne->setField('bar', false);

        $this
            ->objectManager
            ->expects($this->once())
            ->method('find')
            ->with(
                Asset\SimpleEntity::class,
                ['id' => 1]
            )
            ->will($this->returnValue($entityInDatabaseWithIdOfOne));

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToOneEntityNotNullable::class, $entity);
        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity->getToOne(false));
        $this->assertSame($entityInDatabaseWithIdOfOne, $entity->getToOne(false));
        $this->assertEquals(
            'From getter: Modified from setToOne setter',
            $entityInDatabaseWithIdOfOne->getField()
        );
    }

    public function testHydrateOneToOneAssociationByReferenceUsingIdentifierArrayForRelation()
    {
        // When using hydration by reference, it won't use the public API of the entity to set values (setters)
        $entity = new Asset\OneToOneEntity();
        $this->configureObjectManagerForOneToOneEntity();

        // Use entity of id 1 as relation
        $data = ['toOne' => ['id' => 1]];

        $entityInDatabaseWithIdOfOne = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfOne->setId(1);
        $entityInDatabaseWithIdOfOne->setField('bar', false);

        $this
            ->objectManager
            ->expects($this->once())
            ->method('find')
            ->with(Asset\SimpleEntity::class, ['id' => 1])
            ->will($this->returnValue($entityInDatabaseWithIdOfOne));

        $entity = $this->hydratorByReference->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToOneEntity::class, $entity);
        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity->getToOne(false));
        $this->assertSame($entityInDatabaseWithIdOfOne, $entity->getToOne(false));
    }

    public function testCanHydrateOneToOneAssociationByValueWithNullableRelation()
    {
        // When using hydration by value, it will use the public API of the entity to retrieve values (setters)
        $entity = new Asset\OneToOneEntity();
        $this->configureObjectManagerForOneToOneEntity();

        $data = ['toOne' => null];

        $this->metadata->expects($this->once())
                       ->method('hasAssociation');

        $object = $this->hydratorByValue->hydrate($data, $entity);
        $this->assertNull($object->getToOne(false));
    }

    public function testCanHydrateOneToOneAssociationByReferenceWithNullableRelation()
    {
        // When using hydration by reference, it won't use the public API of the entity to retrieve values (setters)
        $entity = new Asset\OneToOneEntity();

        $this->configureObjectManagerForOneToOneEntity();
        $this->objectManager->expects($this->never())->method('find');
        $this->metadata->expects($this->once())->method('hasAssociation');

        $data = ['toOne' => null];

        $object = $this->hydratorByReference->hydrate($data, $entity);
        $this->assertNull($object->getToOne(false));
    }

    public function testExtractOneToManyAssociationByValue()
    {
        // When using extraction by value, it will use the public API of the entity to retrieve values (getters)
        $toMany1 = new Asset\SimpleEntity();
        $toMany1->setId(2);
        $toMany1->setField('foo', false);

        $toMany2 = new Asset\SimpleEntity();
        $toMany2->setId(3);
        $toMany2->setField('bar', false);

        $collection = new ArrayCollection([$toMany1, $toMany2]);

        $entity = new Asset\OneToManyEntity();
        $entity->setId(4);
        $entity->addEntities($collection);

        $this->configureObjectManagerForOneToManyEntity();

        $data = $this->hydratorByValue->extract($entity);

        $this->assertEquals(4, $data['id']);
        $this->assertInstanceOf(Collection::class, $data['entities']);

        $this->assertEquals($toMany1->getId(), $data['entities'][0]->getId());
        $this->assertSame($toMany1, $data['entities'][0]);
        $this->assertEquals($toMany2->getId(), $data['entities'][1]->getId());
        $this->assertSame($toMany2, $data['entities'][1]);
    }

    /**
     * @depends testExtractOneToManyAssociationByValue
     */
    public function testExtractOneToManyByValueWithArray()
    {
        // When using extraction by value, it will use the public API of the entity to retrieve values (getters)
        $toMany1 = new Asset\SimpleEntity();
        $toMany1->setId(2);
        $toMany1->setField('foo', false);

        $toMany2 = new Asset\SimpleEntity();
        $toMany2->setId(3);
        $toMany2->setField('bar', false);

        $collection = new ArrayCollection([$toMany1, $toMany2]);

        $entity = new Asset\OneToManyArrayEntity();
        $entity->setId(4);
        $entity->addEntities($collection);

        $this->configureObjectManagerForOneToManyArrayEntity();

        $data = $this->hydratorByValue->extract($entity);

        $this->assertEquals(4, $data['id']);
        $this->assertInternalType('array', $data['entities']);

        $this->assertEquals($toMany1->getId(), $data['entities'][0]->getId());
        $this->assertSame($toMany1, $data['entities'][0]);
        $this->assertEquals($toMany2->getId(), $data['entities'][1]->getId());
        $this->assertSame($toMany2, $data['entities'][1]);
    }

    public function testExtractOneToManyAssociationByReference()
    {
        // When using extraction by reference, it won't use the public API of the entity to retrieve values (getters)
        $toMany1 = new Asset\SimpleEntity();
        $toMany1->setId(2);
        $toMany1->setField('foo', false);

        $toMany2 = new Asset\SimpleEntity();
        $toMany2->setId(3);
        $toMany2->setField('bar', false);

        $collection = new ArrayCollection([$toMany1, $toMany2]);

        $entity = new Asset\OneToManyEntity();
        $entity->setId(4);
        $entity->addEntities($collection);

        $this->configureObjectManagerForOneToManyEntity();

        $data = $this->hydratorByReference->extract($entity);

        $this->assertEquals(4, $data['id']);
        $this->assertInstanceOf(Collection::class, $data['entities']);

        $this->assertEquals($toMany1->getId(), $data['entities'][0]->getId());
        $this->assertSame($toMany1, $data['entities'][0]);
        $this->assertEquals($toMany2->getId(), $data['entities'][1]->getId());
        $this->assertSame($toMany2, $data['entities'][1]);
    }

    /**
     * @depends testExtractOneToManyAssociationByReference
     */
    public function testExtractOneToManyArrayByReference()
    {
        // When using extraction by reference, it won't use the public API of the entity to retrieve values (getters)
        $toMany1 = new Asset\SimpleEntity();
        $toMany1->setId(2);
        $toMany1->setField('foo', false);

        $toMany2 = new Asset\SimpleEntity();
        $toMany2->setId(3);
        $toMany2->setField('bar', false);

        $collection = new ArrayCollection([$toMany1, $toMany2]);

        $entity = new Asset\OneToManyArrayEntity();
        $entity->setId(4);
        $entity->addEntities($collection);

        $this->configureObjectManagerForOneToManyArrayEntity();

        $data = $this->hydratorByReference->extract($entity);

        $this->assertEquals(4, $data['id']);
        $this->assertInstanceOf(Collection::class, $data['entities']);

        $this->assertEquals($toMany1->getId(), $data['entities'][0]->getId());
        $this->assertSame($toMany1, $data['entities'][0]);
        $this->assertEquals($toMany2->getId(), $data['entities'][1]->getId());
        $this->assertSame($toMany2, $data['entities'][1]);
    }

    public function testHydrateOneToManyAssociationByValue()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $toMany1 = new Asset\SimpleEntity();
        $toMany1->setId(2);
        $toMany1->setField('foo', false);

        $toMany2 = new Asset\SimpleEntity();
        $toMany2->setId(3);
        $toMany2->setField('bar', false);

        $entity = new Asset\OneToManyEntity();
        $this->configureObjectManagerForOneToManyEntity();

        $data = ['entities' => [$toMany1, $toMany2]];

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyEntity::class, $entity);

        $entities = $entity->getEntities(false);

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
            $this->assertContains('Modified from addEntities adder', $en->getField(false));
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($toMany1, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($toMany2, $entities[1]);
    }

    /**
     * @depends testHydrateOneToManyAssociationByValue
     */
    public function testHydrateOneToManyArrayByValue()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $toMany1 = new Asset\SimpleEntity();
        $toMany1->setId(2);
        $toMany1->setField('foo', false);

        $toMany2 = new Asset\SimpleEntity();
        $toMany2->setId(3);
        $toMany2->setField('bar', false);

        $entity = new Asset\OneToManyArrayEntity();
        $this->configureObjectManagerForOneToManyArrayEntity();

        $data = ['entities' => [$toMany1, $toMany2]];

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyArrayEntity::class, $entity);

        $entities = $entity->getEntities(false);

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
            $this->assertContains('Modified from addEntities adder', $en->getField(false));
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($toMany1, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($toMany2, $entities[1]);
    }

    public function testHydrateOneToManyAssociationByReference()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $toMany1 = new Asset\SimpleEntity();
        $toMany1->setId(2);
        $toMany1->setField('foo', false);

        $toMany2 = new Asset\SimpleEntity();
        $toMany2->setId(3);
        $toMany2->setField('bar', false);

        $entity = new Asset\OneToManyEntity();
        $this->configureObjectManagerForOneToManyEntity();

        $data = ['entities' => [$toMany1, $toMany2]];

        $entity = $this->hydratorByReference->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyEntity::class, $entity);

        $entities = $entity->getEntities(false);

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
            $this->assertNotContains('Modified from addEntities adder', $en->getField(false));
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($toMany1, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($toMany2, $entities[1]);
    }

    /**
     * @depends testHydrateOneToManyAssociationByReference
     */
    public function testHydrateOneToManyArrayByReference()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $toMany1 = new Asset\SimpleEntity();
        $toMany1->setId(2);
        $toMany1->setField('foo', false);

        $toMany2 = new Asset\SimpleEntity();
        $toMany2->setId(3);
        $toMany2->setField('bar', false);

        $entity = new Asset\OneToManyArrayEntity();
        $this->configureObjectManagerForOneToManyArrayEntity();

        $data = ['entities' => [$toMany1, $toMany2]];

        $entity = $this->hydratorByReference->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyArrayEntity::class, $entity);

        $entities = $entity->getEntities(false);

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
            $this->assertNotContains('Modified from addEntities adder', $en->getField(false));
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($toMany1, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($toMany2, $entities[1]);
    }

    public function testHydrateOneToManyAssociationByValueUsingIdentifiersForRelations()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $entity = new Asset\OneToManyEntity();
        $this->configureObjectManagerForOneToManyEntity();

        $data = ['entities' => [2, 3]];

        $entityInDatabaseWithIdOfTwo = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfTwo->setId(2);
        $entityInDatabaseWithIdOfTwo->setField('foo', false);

        $entityInDatabaseWithIdOfThree = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfThree->setId(3);
        $entityInDatabaseWithIdOfThree->setField('bar', false);

        $this
            ->objectManager
            ->expects($this->exactly(2))
            ->method('find')
            ->with(
                Asset\SimpleEntity::class,
                $this->logicalOr($this->equalTo(['id' => 2]), $this->equalTo(['id' => 3]))
            )
            ->will(
                $this->returnCallback(
                    function ($target, $arg) use ($entityInDatabaseWithIdOfTwo, $entityInDatabaseWithIdOfThree) {
                        if ($arg['id'] === 2) {
                            return $entityInDatabaseWithIdOfTwo;
                        } elseif ($arg['id'] === 3) {
                            return $entityInDatabaseWithIdOfThree;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyEntity::class, $entity);

        /** @var $entity Asset\OneToManyEntity */
        $entities = $entity->getEntities(false);

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
            $this->assertContains('Modified from addEntities adder', $en->getField(false));
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($entityInDatabaseWithIdOfTwo, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($entityInDatabaseWithIdOfThree, $entities[1]);
    }

    public function testHydrateOneToManyAssociationByValueUsingIdentifiersArrayForRelations()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $entity = new Asset\OneToManyEntity();
        $this->configureObjectManagerForOneToManyEntity();

        $data = [
            'entities' => [
                ['id' => 2],
                ['id' => 3],
            ],
        ];

        $entityInDatabaseWithIdOfTwo = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfTwo->setId(2);
        $entityInDatabaseWithIdOfTwo->setField('foo', false);

        $entityInDatabaseWithIdOfThree = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfThree->setId(3);
        $entityInDatabaseWithIdOfThree->setField('bar', false);

        $this
            ->objectManager
            ->expects($this->exactly(2))
            ->method('find')
            ->with(
                Asset\SimpleEntity::class,
                $this->logicalOr($this->equalTo(['id' => 2]), $this->equalTo(['id' => 3]))
            )
            ->will(
                $this->returnCallback(
                    function ($target, $arg) use ($entityInDatabaseWithIdOfTwo, $entityInDatabaseWithIdOfThree) {
                        if ($arg['id'] === 2) {
                            return $entityInDatabaseWithIdOfTwo;
                        } elseif ($arg['id'] === 3) {
                            return $entityInDatabaseWithIdOfThree;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyEntity::class, $entity);

        /** @var $entity Asset\OneToManyEntity */
        $entities = $entity->getEntities(false);

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
            $this->assertContains('Modified from addEntities adder', $en->getField(false));
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($entityInDatabaseWithIdOfTwo, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($entityInDatabaseWithIdOfThree, $entities[1]);
    }

    public function testHydrateOneToManyAssociationByReferenceUsingIdentifiersArrayForRelations()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $entity = new Asset\OneToManyEntity();
        $this->configureObjectManagerForOneToManyEntity();

        $data = [
            'entities' => [
                ['id' => 2],
                ['id' => 3],
            ],
        ];

        $entityInDatabaseWithIdOfTwo = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfTwo->setId(2);
        $entityInDatabaseWithIdOfTwo->setField('foo', false);

        $entityInDatabaseWithIdOfThree = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfThree->setId(3);
        $entityInDatabaseWithIdOfThree->setField('bar', false);

        $this
            ->objectManager
            ->expects($this->exactly(2))
            ->method('find')
            ->with(
                Asset\SimpleEntity::class,
                $this->logicalOr($this->equalTo(['id' => 2]), $this->equalTo(['id' => 3]))
            )
            ->will(
                $this->returnCallback(
                    function ($target, $arg) use ($entityInDatabaseWithIdOfTwo, $entityInDatabaseWithIdOfThree) {
                        if ($arg['id'] === 2) {
                            return $entityInDatabaseWithIdOfTwo;
                        } elseif ($arg['id'] === 3) {
                            return $entityInDatabaseWithIdOfThree;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $entity = $this->hydratorByReference->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyEntity::class, $entity);

        $entities = $entity->getEntities(false);

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
            $this->assertNotContains('Modified from addEntities adder', $en->getField(false));
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($entityInDatabaseWithIdOfTwo, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($entityInDatabaseWithIdOfThree, $entities[1]);
    }

    public function testHydrateOneToManyAssociationByReferenceUsingIdentifiersForRelations()
    {
        // When using hydration by reference, it won't use the public API of the entity to set values (setters)
        $entity = new Asset\OneToManyEntity();
        $this->configureObjectManagerForOneToManyEntity();

        $data = ['entities' => [2, 3]];

        $entityInDatabaseWithIdOfTwo = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfTwo->setId(2);
        $entityInDatabaseWithIdOfTwo->setField('foo', false);

        $entityInDatabaseWithIdOfThree = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfThree->setId(3);
        $entityInDatabaseWithIdOfThree->setField('bar', false);

        $this
            ->objectManager
            ->expects($this->any())
            ->method('find')
            ->with(
                Asset\SimpleEntity::class,
                $this->logicalOr($this->equalTo(['id' => 2]), $this->equalTo(['id' => 3]))
            )
            ->will(
                $this->returnCallback(
                    function ($target, $arg) use ($entityInDatabaseWithIdOfTwo, $entityInDatabaseWithIdOfThree) {
                        if ($arg['id'] === 2) {
                            return $entityInDatabaseWithIdOfTwo;
                        } elseif ($arg['id'] === 3) {
                            return $entityInDatabaseWithIdOfThree;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $entity = $this->hydratorByReference->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyEntity::class, $entity);

        $entities = $entity->getEntities(false);

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
            $this->assertNotContains('Modified from addEntities adder', $en->getField(false));
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($entityInDatabaseWithIdOfTwo, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($entityInDatabaseWithIdOfThree, $entities[1]);
    }

    public function testHydrateOneToManyAssociationByValueUsingDisallowRemoveStrategy()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $toMany1 = new Asset\SimpleEntity();
        $toMany1->setId(2);
        $toMany1->setField('foo', false);

        $toMany2 = new Asset\SimpleEntity();
        $toMany2->setId(3);
        $toMany2->setField('bar', false);

        $toMany3 = new Asset\SimpleEntity();
        $toMany3->setId(8);
        $toMany3->setField('baz', false);

        $entity = new Asset\OneToManyEntity();
        $this->configureObjectManagerForOneToManyEntity();

        // Initially add two elements
        $entity->addEntities(new ArrayCollection([$toMany1, $toMany2]));

        // The hydrated collection contains two other elements, one of them is new, and one of them is missing
        // in the new strategy
        $data = ['entities' => [$toMany2, $toMany3]];

        // Use a DisallowRemove strategy
        $this->hydratorByValue->addStrategy('entities', new Strategy\DisallowRemoveByValue());
        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $entities = $entity->getEntities(false);

        // DisallowStrategy should not remove existing entities in Collection even if it's not in the new collection
        $this->assertEquals(3, count($entities));

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($toMany1, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($toMany2, $entities[1]);

        $this->assertEquals(8, $entities[2]->getId());
        $this->assertSame($toMany3, $entities[2]);
    }

    public function testHydrateOneToManyAssociationByReferenceUsingDisallowRemoveStrategy()
    {
       // When using hydration by reference, it won't use the public API of the entity to set values (setters)
        $toMany1 = new Asset\SimpleEntity();
        $toMany1->setId(2);
        $toMany1->setField('foo', false);

        $toMany2 = new Asset\SimpleEntity();
        $toMany2->setId(3);
        $toMany2->setField('bar', false);

        $toMany3 = new Asset\SimpleEntity();
        $toMany3->setId(8);
        $toMany3->setField('baz', false);

        $entity = new Asset\OneToManyEntity();
        $this->configureObjectManagerForOneToManyEntity();

        // Initially add two elements
        $entity->addEntities(new ArrayCollection([$toMany1, $toMany2]));

        // The hydrated collection contains two other elements, one of them is new, and one of them is missing
        // in the new strategy
        $data = ['entities' => [$toMany2, $toMany3]];

        // Use a DisallowRemove strategy
        $this->hydratorByReference->addStrategy('entities', new Strategy\DisallowRemoveByReference());
        $entity = $this->hydratorByReference->hydrate($data, $entity);

        $entities = $entity->getEntities(false);

        // DisallowStrategy should not remove existing entities in Collection even if it's not in the new collection
        $this->assertEquals(3, count($entities));

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());

            // Only the third element is new so the adder has not been called on it
            if ($en === $toMany3) {
                $this->assertNotContains('Modified from addEntities adder', $en->getField(false));
            }
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($toMany1, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($toMany2, $entities[1]);

        $this->assertEquals(8, $entities[2]->getId());
        $this->assertSame($toMany3, $entities[2]);
    }

    public function testHydrateOneToManyAssociationByValueWithArrayCausingDataModifications()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $data = [
            'entities' => [
                ['id' => 2, 'field' => 'Modified By Hydrate'],
                ['id' => 3, 'field' => 'Modified By Hydrate'],
            ],
        ];

        $entityInDatabaseWithIdOfTwo = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfTwo->setId(2);
        $entityInDatabaseWithIdOfTwo->setField('foo', false);

        $entityInDatabaseWithIdOfThree = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfThree->setId(3);
        $entityInDatabaseWithIdOfThree->setField('bar', false);

        $entity = new Asset\OneToManyEntityWithEntities(
            new ArrayCollection([
                $entityInDatabaseWithIdOfTwo,
                $entityInDatabaseWithIdOfThree,
            ])
        );
        $this->configureObjectManagerForOneToManyEntity();

        $this
            ->objectManager
            ->expects($this->exactly(2))
            ->method('find')
            ->with(
                Asset\SimpleEntity::class,
                $this->logicalOr($this->equalTo(['id' => 2]), $this->equalTo(['id' => 3]))
            )
            ->will(
                $this->returnCallback(
                    function ($target, $arg) use ($entityInDatabaseWithIdOfTwo, $entityInDatabaseWithIdOfThree) {
                        if ($arg['id'] === 2) {
                            return $entityInDatabaseWithIdOfTwo;
                        } elseif ($arg['id'] === 3) {
                            return $entityInDatabaseWithIdOfThree;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyEntityWithEntities::class, $entity);

        /** @var $entity Asset\OneToManyEntity */
        $entities = $entity->getEntities(false);

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
            $this->assertInternalType('string', $en->getField());
            $this->assertContains('Modified By Hydrate', $en->getField(false));
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($entityInDatabaseWithIdOfTwo, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($entityInDatabaseWithIdOfThree, $entities[1]);
    }

    public function testHydrateOneToManyAssociationByValueWithTraversableCausingDataModifications()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $data = [
            'entities' => new ArrayCollection(
                [
                    ['id' => 2, 'field' => 'Modified By Hydrate'],
                    ['id' => 3, 'field' => 'Modified By Hydrate'],
                ]
            ),
        ];

        $entityInDatabaseWithIdOfTwo = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfTwo->setId(2);
        $entityInDatabaseWithIdOfTwo->setField('foo', false);

        $entityInDatabaseWithIdOfThree = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfThree->setId(3);
        $entityInDatabaseWithIdOfThree->setField('bar', false);

        $entity = new Asset\OneToManyEntityWithEntities(
            new ArrayCollection([
                $entityInDatabaseWithIdOfTwo,
                $entityInDatabaseWithIdOfThree,
            ])
        );
        $this->configureObjectManagerForOneToManyEntity();

        $this
            ->objectManager
            ->expects($this->exactly(2))
            ->method('find')
            ->with(
                Asset\SimpleEntity::class,
                $this->logicalOr($this->equalTo(['id' => 2]), $this->equalTo(['id' => 3]))
            )
            ->will(
                $this->returnCallback(
                    function ($target, $arg) use ($entityInDatabaseWithIdOfTwo, $entityInDatabaseWithIdOfThree) {
                        if ($arg['id'] === 2) {
                            return $entityInDatabaseWithIdOfTwo;
                        } elseif ($arg['id'] === 3) {
                            return $entityInDatabaseWithIdOfThree;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyEntityWithEntities::class, $entity);

        /** @var $entity Asset\OneToManyEntity */
        $entities = $entity->getEntities(false);

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
            $this->assertInternalType('string', $en->getField());
            $this->assertContains('Modified By Hydrate', $en->getField(false));
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($entityInDatabaseWithIdOfTwo, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($entityInDatabaseWithIdOfThree, $entities[1]);
    }

    public function testHydrateOneToManyAssociationByValueWithStdClass()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $stdClass1     = new \stdClass();
        $stdClass1->id = 2;

        $stdClass2     = new \stdClass();
        $stdClass2->id = 3;

        $data = ['entities' => [$stdClass1, $stdClass2]];

        $entityInDatabaseWithIdOfTwo = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfTwo->setId(2);
        $entityInDatabaseWithIdOfTwo->setField('foo', false);

        $entityInDatabaseWithIdOfThree = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfThree->setId(3);
        $entityInDatabaseWithIdOfThree->setField('bar', false);

        $entity = new Asset\OneToManyEntityWithEntities(
            new ArrayCollection([
                $entityInDatabaseWithIdOfTwo,
                $entityInDatabaseWithIdOfThree,
            ])
        );
        $this->configureObjectManagerForOneToManyEntity();

        $this
            ->objectManager
            ->expects($this->exactly(2))
            ->method('find')
            ->with(
                Asset\SimpleEntity::class,
                $this->logicalOr($this->equalTo(['id' => 2]), $this->equalTo(['id' => 3]))
            )
            ->will(
                $this->returnCallback(
                    function ($target, $arg) use ($entityInDatabaseWithIdOfTwo, $entityInDatabaseWithIdOfThree) {
                        if ($arg['id'] === 2) {
                            return $entityInDatabaseWithIdOfTwo;
                        } elseif ($arg['id'] === 3) {
                            return $entityInDatabaseWithIdOfThree;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyEntityWithEntities::class, $entity);

        /** @var $entity Asset\OneToManyEntity */
        $entities = $entity->getEntities(false);

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($entityInDatabaseWithIdOfTwo, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($entityInDatabaseWithIdOfThree, $entities[1]);
    }

    public function testHydrateOneToManyAssociationByReferenceWithArrayCausingDataModifications()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $data = [
            'entities' => [
                ['id' => 2, 'field' => 'Modified By Hydrate'],
                ['id' => 3, 'field' => 'Modified By Hydrate'],
            ],
        ];

        $entityInDatabaseWithIdOfTwo = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfTwo->setId(2);
        $entityInDatabaseWithIdOfTwo->setField('Unmodified Value', false);

        $entityInDatabaseWithIdOfThree = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfThree->setId(3);
        $entityInDatabaseWithIdOfThree->setField('Unmodified Value', false);

        $entity = new Asset\OneToManyEntityWithEntities(
            new ArrayCollection([
                $entityInDatabaseWithIdOfTwo,
                $entityInDatabaseWithIdOfThree,
            ])
        );

        $reflSteps = [
            new \ReflectionClass(Asset\OneToManyEntityWithEntities::class),
            new \ReflectionClass(Asset\SimpleEntity::class),
            new \ReflectionClass(Asset\SimpleEntity::class),
            new \ReflectionClass(Asset\OneToManyEntityWithEntities::class),
        ];
        $this
            ->metadata
            ->expects($this->any())
            ->method('getReflectionClass')
            ->will($this->returnCallback(
                function () use (&$reflSteps) {
                    $refl = array_shift($reflSteps);
                    return $refl;
                }
            ));

        $this->configureObjectManagerForOneToManyEntity();

        $this
            ->objectManager
            ->expects($this->exactly(2))
            ->method('find')
            ->with(
                Asset\SimpleEntity::class,
                $this->logicalOr($this->equalTo(['id' => 2]), $this->equalTo(['id' => 3]))
            )
            ->will(
                $this->returnCallback(
                    function ($target, $arg) use ($entityInDatabaseWithIdOfTwo, $entityInDatabaseWithIdOfThree) {
                        if ($arg['id'] === 2) {
                            return $entityInDatabaseWithIdOfTwo;
                        } elseif ($arg['id'] === 3) {
                            return $entityInDatabaseWithIdOfThree;
                        }

                        throw new \InvalidArgumentException();
                    }
                )
            );

        $entity = $this->hydratorByReference->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyEntityWithEntities::class, $entity);

        /** @var $entity Asset\OneToManyEntity */
        $entities = $entity->getEntities(false);

        foreach ($entities as $en) {
            $this->assertInstanceOf(Asset\SimpleEntity::class, $en);
            $this->assertInternalType('integer', $en->getId());
            $this->assertInternalType('string', $en->getField());
            $this->assertContains('Modified By Hydrate', $en->getField(false));
        }

        $this->assertEquals(2, $entities[0]->getId());
        $this->assertSame($entityInDatabaseWithIdOfTwo, $entities[0]);

        $this->assertEquals(3, $entities[1]->getId());
        $this->assertSame($entityInDatabaseWithIdOfThree, $entities[1]);
    }

    public function testAssertCollectionsAreNotSwappedDuringHydration()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $entity = new Asset\OneToManyEntity();
        $this->configureObjectManagerForOneToManyEntity();

        $toMany1 = new Asset\SimpleEntity();
        $toMany1->setId(2);
        $toMany1->setField('foo', false);

        $toMany2 = new Asset\SimpleEntity();
        $toMany2->setId(3);
        $toMany2->setField('bar', false);

        $data = ['entities' => [$toMany1, $toMany2]];

        // Set the initial collection
        $entity->addEntities(new ArrayCollection([$toMany1, $toMany2]));
        $initialCollection = $entity->getEntities(false);

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $modifiedCollection = $entity->getEntities(false);
        $this->assertSame($initialCollection, $modifiedCollection);
    }

    public function testAssertCollectionsAreNotSwappedDuringHydrationUsingIdentifiersForRelations()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $entity = new Asset\OneToManyEntity();
        $this->configureObjectManagerForOneToManyEntity();

        $data = ['entities' => [2, 3]];

        $entityInDatabaseWithIdOfTwo = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfTwo->setId(2);
        $entityInDatabaseWithIdOfTwo->setField('foo', false);

        $entityInDatabaseWithIdOfThree = new Asset\SimpleEntity();
        $entityInDatabaseWithIdOfThree->setId(3);
        $entityInDatabaseWithIdOfThree->setField('bar', false);

        // Set the initial collection
        $entity->addEntities(new ArrayCollection([$entityInDatabaseWithIdOfTwo, $entityInDatabaseWithIdOfThree]));
        $initialCollection = $entity->getEntities(false);

        $this
            ->objectManager
            ->expects($this->any())
            ->method('find')
            ->with(
                Asset\SimpleEntity::class,
                $this->logicalOr($this->equalTo(['id' =>2]), $this->equalTo(['id' => 3]))
            )
            ->will(
                $this->returnCallback(
                    function ($target, $arg) use ($entityInDatabaseWithIdOfTwo, $entityInDatabaseWithIdOfThree) {
                        if ($arg['id'] === 2) {
                            return $entityInDatabaseWithIdOfTwo;
                        }

                        return $entityInDatabaseWithIdOfThree;
                    }
                )
            );

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $modifiedCollection = $entity->getEntities(false);
        $this->assertSame($initialCollection, $modifiedCollection);
    }

    public function testCanLookupsForEmptyIdentifiers()
    {
        // When using hydration by reference, it won't use the public API of the entity to set values (setters)
        $entity = new Asset\OneToManyEntity();
        $this->configureObjectManagerForOneToManyEntity();

        $data = ['entities' => ['']];

        $entityInDatabaseWithEmptyId = new Asset\SimpleEntity();
        $entityInDatabaseWithEmptyId->setId('');
        $entityInDatabaseWithEmptyId->setField('baz', false);

        $this
            ->objectManager
            ->expects($this->any())
            ->method('find')
            ->with(Asset\SimpleEntity::class, '')
            ->will($this->returnValue($entityInDatabaseWithEmptyId));

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\OneToManyEntity::class, $entity);

        $entities = $entity->getEntities(false);
        $entity   = $entities[0];

        $this->assertEquals(1, count($entities));

        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity);
        $this->assertSame($entityInDatabaseWithEmptyId, $entity);
    }

    public function testHandleDateTimeConversionUsingByValue()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $entity = new Asset\SimpleEntityWithDateTime();
        $this->configureObjectManagerForSimpleEntityWithDateTime();

        $now  = time();
        $data = ['date' => $now];

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf('DateTime', $entity->getDate());
        $this->assertEquals($now, $entity->getDate()->getTimestamp());
    }

    public function testEmptyStringIsNotConvertedToDateTime()
    {
        $entity = new Asset\SimpleEntityWithDateTime();
        $this->configureObjectManagerForSimpleEntityWithDateTime();

        $data = ['date' => ''];

        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertNull($entity->getDate());
    }

    public function testAssertNullValueHydratedForOneToOneWithOptionalMethodSignature()
    {
        $entity = new Asset\OneToOneEntity();

        $this->configureObjectManagerForOneToOneEntity();
        $this->objectManager->expects($this->never())->method('find');

        $data = ['toOne' => null];

        $object = $this->hydratorByValue->hydrate($data, $entity);
        $this->assertNull($object->getToOne(false));
    }

    public function testAssertNullValueNotUsedAsIdentifierForOneToOneWithNonOptionalMethodSignature()
    {
        $entity = new Asset\OneToOneEntityNotNullable();

        $entity->setToOne(new Asset\SimpleEntity());
        $this->configureObjectManagerForOneToOneEntityNotNullable();
        $this->objectManager->expects($this->never())->method('find');

        $data = ['toOne' => null];

        $object = $this->hydratorByValue->hydrate($data, $entity);
        $this->assertInstanceOf(Asset\SimpleEntity::class, $object->getToOne(false));
    }

    public function testUsesStrategyOnSimpleFieldsWhenHydratingByValue()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $entity = new Asset\SimpleEntity();
        $this->configureObjectManagerForSimpleEntity();
        $data = ['field' => 'foo'];

        $this->hydratorByValue->addStrategy('field', new Asset\SimpleStrategy());
        $entity = $this->hydratorByValue->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity);
        $this->assertEquals('From setter: modified while hydrating', $entity->getField(false));
    }

    public function testUsesStrategyOnSimpleFieldsWhenHydratingByReference()
    {
        // When using hydration by value, it will use the public API of the entity to set values (setters)
        $entity = new Asset\SimpleEntity();
        $this->configureObjectManagerForSimpleEntity();
        $data = ['field' => 'foo'];

        $this->hydratorByReference->addStrategy('field', new Asset\SimpleStrategy());
        $entity = $this->hydratorByReference->hydrate($data, $entity);

        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity);
        $this->assertEquals('modified while hydrating', $entity->getField(false));
    }

    public function testUsesStrategyOnSimpleFieldsWhenExtractingByValue()
    {
        $entity = new Asset\SimpleEntity();
        $entity->setId(2);
        $entity->setField('foo', false);

        $this->configureObjectManagerForSimpleEntity();

        $this->hydratorByValue->addStrategy('field', new Asset\SimpleStrategy());
        $data = $this->hydratorByValue->extract($entity);
        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity);
        $this->assertEquals(['id' => 2, 'field' => 'modified while extracting'], $data);
    }

    public function testUsesStrategyOnSimpleFieldsWhenExtractingByReference()
    {
        $entity = new Asset\SimpleEntity();
        $entity->setId(2);
        $entity->setField('foo', false);

        $this->configureObjectManagerForSimpleEntity();

        $this->hydratorByReference->addStrategy('field', new Asset\SimpleStrategy());
        $data = $this->hydratorByReference->extract($entity);
        $this->assertInstanceOf(Asset\SimpleEntity::class, $entity);
        $this->assertEquals(['id' => 2, 'field' => 'modified while extracting'], $data);
    }

    public function testCanExtractIsserByValue()
    {
        $entity = new Asset\SimpleIsEntity();
        $entity->setId(2);
        $entity->setDone(true);

        $this->configureObjectManagerForSimpleIsEntity();

        $data = $this->hydratorByValue->extract($entity);
        $this->assertInstanceOf(Asset\SimpleIsEntity::class, $entity);
        $this->assertEquals(['id' => 2, 'done' => true], $data);
    }

    public function testCanExtractIsserThatStartsWithIsByValue()
    {
        $entity = new Asset\SimpleEntityWithIsBoolean();
        $entity->setId(2);
        $entity->setIsActive(true);

        $this->configureObjectManagerForSimpleEntityWithIsBoolean();

        $data = $this->hydratorByValue->extract($entity);
        $this->assertInstanceOf(Asset\SimpleEntityWithIsBoolean::class, $entity);
        $this->assertEquals(['id' => 2, 'isActive' => true], $data);
    }

    public function testExtractWithPropertyNameFilterByValue()
    {
        $entity = new Asset\SimpleEntity();
        $entity->setId(2);
        $entity->setField('foo', false);

        $filter = new Filter\PropertyName(['id'], false);

        $this->configureObjectManagerForSimpleEntity();

        $this->hydratorByValue->addFilter('propertyname', $filter);
        $data = $this->hydratorByValue->extract($entity);

        $this->assertEquals(2, $data['id']);
        $this->assertEquals(['id'], array_keys($data), 'Only the "id" field should have been extracted.');
    }

    public function testExtractWithPropertyNameFilterByReference()
    {
        $entity = new Asset\SimpleEntity();
        $entity->setId(2);
        $entity->setField('foo', false);

        $filter = new Filter\PropertyName(['id'], false);

        $this->configureObjectManagerForSimpleEntity();

        $this->hydratorByReference->addFilter('propertyname', $filter);
        $data = $this->hydratorByReference->extract($entity);

        $this->assertEquals(2, $data['id']);
        $this->assertEquals(['id'], array_keys($data), 'Only the "id" field should have been extracted.');
    }

    public function testExtractByReferenceUsesNamingStrategy()
    {
        $this->configureObjectManagerForNamingStrategyEntity();
        $name = 'Foo';
        $this->hydratorByReference->setNamingStrategy(new UnderscoreNamingStrategy());
        $data = $this->hydratorByReference->extract(new Asset\NamingStrategyEntity($name));
        $this->assertEquals($name, $data['camel_case']);
    }

    public function testExtractByValueUsesNamingStrategy()
    {
        $this->configureObjectManagerForNamingStrategyEntity();
        $name = 'Bar';
        $this->hydratorByValue->setNamingStrategy(new UnderscoreNamingStrategy());
        $data = $this->hydratorByValue->extract(new Asset\NamingStrategyEntity($name));
        $this->assertEquals($name, $data['camel_case']);
    }

    public function testHydrateByReferenceUsesNamingStrategy()
    {
        $this->configureObjectManagerForNamingStrategyEntity();
        $name = 'Baz';
        $this->hydratorByReference->setNamingStrategy(new UnderscoreNamingStrategy());
        $entity = $this->hydratorByReference->hydrate(['camel_case' => $name], new Asset\NamingStrategyEntity());
        $this->assertEquals($name, $entity->getCamelCase());
    }

    public function testHydrateByValueUsesNamingStrategy()
    {
        $this->configureObjectManagerForNamingStrategyEntity();
        $name = 'Qux';
        $this->hydratorByValue->setNamingStrategy(new UnderscoreNamingStrategy());
        $entity = $this->hydratorByValue->hydrate(['camel_case' => $name], new Asset\NamingStrategyEntity());
        $this->assertEquals($name, $entity->getCamelCase());
    }
}
