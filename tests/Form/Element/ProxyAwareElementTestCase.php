<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace DoctrineModuleTest\Form\Element;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class ProxyAwareElementTestCase extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ArrayCollection
     */
    protected $values;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject|ClassMetadata
     */
    protected $metadata;

    /**
     * @var \Zend\Form\Element
     */
    protected $element;

    protected function prepareProxy()
    {
        $objectClass = TestAsset\FormObject::class;
        $objectOne   = new TestAsset\FormObject();
        $objectTwo   = new TestAsset\FormObject();

        $objectOne->setId(1)
            ->setUsername('object one username')
            ->setPassword('object one password')
            ->setEmail('object one email')
            ->setFirstname('object one firstname')
            ->setSurname('object one surname');

        $objectTwo->setId(2)
            ->setUsername('object two username')
            ->setPassword('object two password')
            ->setEmail('object two email')
            ->setFirstname('object two firstname')
            ->setSurname('object two surname');

        $result       = new ArrayCollection([$objectOne, $objectTwo]);
        $this->values = $result;

        $metadata = $this->createMock(ClassMetadata::class);
        $metadata
            ->expects($this->any())
            ->method('getIdentifierValues')
            ->will(
                $this->returnCallback(
                    function () use ($objectOne, $objectTwo) {
                        $input = func_get_args();
                        $input = array_shift($input);

                        if ($input == $objectOne) {
                            return ['id' => 1];
                        } elseif ($input == $objectTwo) {
                            return ['id' => 2];
                        }

                        return [];
                    }
                )
            );

        $objectRepository = $this->createMock(ObjectRepository::class);
        $objectRepository->expects($this->any())
            ->method('findAll')
            ->will($this->returnValue($result));

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo($objectClass))
            ->will($this->returnValue($metadata));

        $objectManager
            ->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo($objectClass))
            ->will($this->returnValue($objectRepository));

        $this->element->getProxy()->setOptions([
            'object_manager' => $objectManager,
            'target_class'   => $objectClass,
        ]);

        $this->metadata = $metadata;
    }

    /**
     * Proxy should stay read only, use with care
     *
     * @param $proxy
     * @param $element
     */
    protected function setProxyViaReflection($proxy, $element = null)
    {
        if (! $element) {
            $element = $this->element;
        }

        $prop = new \ReflectionProperty(get_class($this->element), 'proxy');
        $prop->setAccessible(true);
        $prop->setValue($element, $proxy);
    }
}
