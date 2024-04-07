<?php $__env->startSection('content'); ?> 



<div class="relative m-10 overflow-x-auto shadow-md rounded-lg">
<table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400" >
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400" >
            <tr>
                <th scope="col" class="px-6 py-3">ID</th>
                <th scope="col" class="px-6 py-3">Name</th>
                <th scope="col" class="px-6 py-3">Email</th>
                <th scope="col" class="px-6 py-3">Phone</th>
                <th scope="col" class="px-6 py-3">Course</th>
                <!-- <th scope="col" class="px-6 py-3">🗑️</th> -->
            </tr>
        </thead>
        <tbody>
            <?php $__currentLoopData = $inscriptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $inscription): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr class="bg-white border-b dark:bg-gray-400 dark:border-gray-700">
                    <td scope="row" class="px-6 py-3 font-medium text-white whitespace-nowrap "><?php echo e($inscription->id); ?></td>
                    <td scope="row" class="px-6 py-3 font-medium text-white whitespace-nowrap "><?php echo e($inscription->name); ?></td>
                    <td scope="row" class="px-6 py-3 font-medium text-white whitespace-nowrap "><?php echo e($inscription->email); ?></td>
                    <td scope="row" class="px-6 py-3 font-medium text-white whitespace-nowrap "><?php echo e($inscription->phone); ?></td>
                    <td scope="row" class="px-6 py-3 font-medium text-white whitespace-nowrap "><?php echo e($inscription->course); ?></td>

                   
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
       
    </table> </tbody> <?php echo e($inscriptions -> links()); ?>

    <?php $__env->stopSection(); ?>
</div>


   

<?php echo $__env->make('Layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/hallyaattoungbre/Document/COURS 2023/S2/Projet Annuel/Back_PA/resources/views/inscriptions/index.blade.php ENDPATH**/ ?>