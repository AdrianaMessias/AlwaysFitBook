<?php
if (!defined("LOADED")) exit;

use App\Controllers\AuthController;
use App\Controllers\FallbackController;
use App\Controllers\GalleryController;
use App\Facades\Router;


Router::post("/api/login", [AuthController::class, "login"]);
Router::post("/api/register", [AuthController::class, "register"]);

Router::get("/api/galleries", [GalleryController::class, "index"]);
Router::get("/api/galleries/{id}", [GalleryController::class, "show"]);
Router::post("/api/galleries", [GalleryController::class, "store"]);
Router::put("/api/galleries/{id}", [GalleryController::class, "update"]);
Router::delete("/api/galleries/{id}", [GalleryController::class, "destroy"]);

Router::fallback([FallbackController::class, "fallback"]);
