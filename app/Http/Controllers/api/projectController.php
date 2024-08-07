<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\project;
use App\Models\projectType;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class projectController extends Controller
{
    public function listProjectByType(Request $request){
        try {
            // Inicia a query para buscar os projetos
            $query = project::query();
    
            // Busca todos os tipos de projeto
            $projectTypes = projectType::select('id', 'name')->get();
    
            // Filtra por tipo de projeto se o parâmetro estiver presente na requisição
            if ($request->has('project_type_id')) {
                $project_type_id = $request->project_type_id;
                $query->where('project_type_id', '=', $project_type_id);
            }
    
            // Executa a query e busca os projetos
            $projects = $query->get();
    
            // Adiciona o nome do tipo a cada projeto
            foreach ($projects as $project) {
                foreach ($projectTypes as $type) {
                    if ($project->project_type_id == $type->id) {
                        $project->type_name = $type->name;
                        break;
                    }
                }
            }
    
            return $projects;
        } catch (\Exception $erro) {
            return response()->json(['status' => 'erro', 'details' => $erro->getMessage()], 500);
        }
    }

    public function listProjectById($id) {
        try {
            // Consulta para buscar o projeto pelo ID
            $project = Project::find($id);
    
            // Verifica se o projeto foi encontrado
            if ($project) {
                return response()->json($project, 200);
            } else {
                return response()->json(['status' => 'erro', 'details' => 'Project not found'], 404);
            }
    
        } catch(\Exception $erro) {
            return response()->json(['status' => 'erro', 'details' => $erro->getMessage()], 500);
        }
    }

    public function createProject(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'project_type_id' => 'required|exists:project_type,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // Validations for image
        ]);

        try {
           // Verifica se uma imagem foi enviada com a requisição
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('project_images', 'public');
                // Corrige o formato da URL da imagem
                $imageUrl = Storage::url($imagePath);
                $imageUrl = str_replace('public/', 'storage/', $imageUrl);
                $fullUrl = url($imageUrl);
            } else {
                return response()->json(['status' => 'erro', 'details' => 'Image upload failed'], 400);
            }

            // Create the project
            $project = Project::create([
                'name' => $request->name,
                'description' => $request->description,
                'image_url' => $fullUrl,
                'project_type_id' => $request->project_type_id,
            ]);

            return response()->json(['status' => 'success', 'project' => $project], 201);
        } catch (\Exception $erro) {
            return response()->json(['status' => 'erro', 'details' => $erro->getMessage()], 500);
        }
    }

}
