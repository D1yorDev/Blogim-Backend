<?php
	
	namespace App\Entity\Interfaces;
	
	interface IsDeletedSettableInterface
	{
		public function setIsDeleted(bool $isDeleted): self;
	}