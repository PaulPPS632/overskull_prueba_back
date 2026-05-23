<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Categories', description: 'Category management endpoints')]
class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    #[OA\Get(
        path: '/categories',
        summary: 'List categories',
        description: 'Returns all categories.',
        operationId: 'categories.index',
        tags: ['Categories'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Categories retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Categories retrieved successfully'),
                        new OA\Property(property: 'data', type: 'array', items: new OA\Items(type: 'object')),
                    ]
                )
            ),
        ]
    )]
    public function index()
    {
        //
        $categories = Category::all();
        return $this->successResponse('Categories retrieved successfully', $categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    #[OA\Post(
        path: '/categories',
        summary: 'Create category',
        description: 'Creates a new category.',
        operationId: 'categories.store',
        tags: ['Categories'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['name'],
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Peripherals'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Computer accessories category'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'Category created successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Category created successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation Error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Validation Error'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function store(Request $request)
    {
        //
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        if($validator->fails()){
            return $this->errorResponse('Validation Error', 422, $validator->errors());
        }
        
        $category = Category::create($request->all());

        return $this->successResponse('Category created successfully', $category, 201);
    }

    /**
     * Display the specified resource.
     */
    #[OA\Get(
        path: '/categories/{id}',
        summary: 'Get category by id',
        description: 'Returns a category by id.',
        operationId: 'categories.show',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Category id',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Category retrieved successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Category retrieved successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Category not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Category not found'),
                        new OA\Property(property: 'data', nullable: true, example: null),
                    ]
                )
            ),
        ]
    )]
    public function show(string $id)
    {
        $category = Category::find($id);
        if(!$category){
            return $this->errorResponse('Category not found', 404);
        }
        return $this->successResponse('Category retrieved successfully', $category);
    }

    /**
     * Update the specified resource in storage.
     */
    #[OA\Put(
        path: '/categories/{id}',
        summary: 'Update category',
        description: 'Updates one or more category fields.',
        operationId: 'categories.update',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Category id',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', maxLength: 255, example: 'Updated Category'),
                    new OA\Property(property: 'description', type: 'string', nullable: true, example: 'Updated description'),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Category updated successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Category updated successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Category not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Category not found'),
                        new OA\Property(property: 'data', nullable: true, example: null),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation Error',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Validation Error'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
        ]
    )]
    public function update(Request $request, string $id)
    {
        //
        $category = Category::find($id);
        
        if(!$category){
            return $this->errorResponse('Category not found', 404);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);
        
        if($validator->fails()){
            return $this->errorResponse('Validation Error', 422, $validator->errors());
        }
        
        $category->update($request->all());

        return $this->successResponse('Category updated successfully', $category);
    }

    /**
     * Remove the specified resource from storage.
     */
    #[OA\Delete(
        path: '/categories/{id}',
        summary: 'Delete category',
        description: 'Deletes a category by id.',
        operationId: 'categories.destroy',
        tags: ['Categories'],
        parameters: [
            new OA\Parameter(
                name: 'id',
                in: 'path',
                required: true,
                description: 'Category id',
                schema: new OA\Schema(type: 'integer', example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Category deleted successfully',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: true),
                        new OA\Property(property: 'message', type: 'string', example: 'Category deleted successfully'),
                        new OA\Property(property: 'data', type: 'object'),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: 'Category not found',
                content: new OA\JsonContent(
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'success', type: 'boolean', example: false),
                        new OA\Property(property: 'message', type: 'string', example: 'Category not found'),
                        new OA\Property(property: 'data', nullable: true, example: null),
                    ]
                )
            ),
        ]
    )]
    public function destroy(string $id)
    {
        $category = Category::find($id);
        if(!$category){
            return $this->errorResponse('Category not found', 404);
        }

        $category->delete();
        return $this->successResponse('Category deleted successfully', $category);
    }
}
