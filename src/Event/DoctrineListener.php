<?php
namespace App\EventListener;

use App\Annotation\Encrypt;
use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use App\Service\EncryptService;
use ReflectionClass;

class DoctrineListener
{
    //accès au service
    private $encryptService;
    // initialisation de l'entity à persister
    private $entity = null;

    public function __construct(EncryptService $encryptService)
    {
        $this->encryptService = $encryptService;
    }

    // Méthodes écoutées :
    // - prePersist et preUpdate avant l'enregistrement des entités
    // - postLoad après le chargement des entités
    // LifecycleEventArgs $args est obligatoire ici et ne peut pas être injecté ailleurs
    public function prePersist(LifecycleEventArgs $args)
    {
        $this->doAction('encrypt', $args);
    }
    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->doAction('encrypt', $args);
    }
    public function postLoad(LifecycleEventArgs $args)
    {
        $this->doAction('decrypt', $args);
    }

    /*
     * On récupère l'entity à persister
     * S'il y a des champs à traiter (encrypt ou decrypt), on les traite (proceed)
     * $action va correspondre à la méthode (encrypt ou decrypt) appelée dans le service encryptService
     */
    private function doAction($action, $args){
        $this->entity = $args->getObject(); // 'App\Entity\User' par exemple
        $reader = new AnnotationReader(); // lit les annotations de l'entité à persister
        foreach ($this->getFields() as $field) {
            $annotations = $reader->getPropertyAnnotations($field);// récupère les annotation du champ
            if($this->fieldHasEncryptAnnotation($annotations)){ // s'il y a une annotation @Encrypt
                $this->proceed(ucfirst($field->name), $action); // e.g $this->proceed('Name', 'encrypt')
            }
        }
    }


    /**
     * @param $fieldName
     * @param $action
     */
    private function proceed($fieldName, $action) {
        $getMethod = 'get' . $fieldName; // le getter du champ e.g getName
        $setMethod = 'set' . $fieldName; // le setter du champ e.g setName
        $modifiedField = $this->encryptService->$action( $this->entity->$getMethod() ); // on encrypte ou on décrypte
        $this->entity->$setMethod($modifiedField); // on met à jour l'entity
    }

    /*
     * Retourne true si une des annotations passées en paramètre est @Encrypt, false sinon.
     */
    private function fieldHasEncryptAnnotation($annotations): bool{
        foreach ($annotations as $annotation) {
            if ($annotation instanceof Encrypt) { // s'il y a une annotation @Encrypt
                return true;
            }
        }
        return false;
    }

    /*
     * Retourne tous les champs de l'entity
     */
    private function getFields(): array {
        $refClass = new ReflectionClass($this->entity);
        return $refClass->getProperties(); // récupère toutes les propriétés de l'objet à persister
    }
}
