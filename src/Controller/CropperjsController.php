<?php

namespace App\Controller;

use App\Service\FileUploaderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\UX\Cropperjs\Factory\CropperInterface;
use Symfony\UX\Cropperjs\Form\CropperType;

class CropperjsController extends AbstractController
{

    #[Route('/cropperjs/{filename}', name: 'app_cropperjs')]
    public function index(
        CropperInterface $cropper, 
        Request $request, 
        FileUploaderService $fileUploaderService,
        $filename,
    ): Response
    {
        $crop = $cropper->createCrop('uploads/img/'.$filename);
        $crop->setCroppedMaxSize(170, 170);

        $form = $this->createFormBuilder(['crop' => $crop])
            ->add('crop', CropperType::class, [
                'public_url' => '/uploads/img/'.$filename,
                'cropper_options' => [
                    'aspectRatio' => 170 / 170,
                    'viewMode' => 2,
                    'minContainerHeight' => 500
                ],
            ])
            ->getForm()
        ;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get the cropped image data (as a string)
            $croppedImageData = $crop->getCroppedImage();

            // Upload the cropped image
            $fileUploaderService->uploadCroppedImage($croppedImageData, $filename);

            return $this->redirectToRoute('app_teammate_index', ['category_label' => 'association'], Response::HTTP_SEE_OTHER);
        }
        
        return $this->render('cropperjs/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
