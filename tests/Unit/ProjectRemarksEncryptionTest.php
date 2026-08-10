<?php

namespace Tests\Unit;

use App\Models\Project;
use Tests\TestCase;

class ProjectRemarksEncryptionTest extends TestCase
{
    public function test_project_remarks_are_encrypted_when_saved_and_decrypted_when_read(): void
    {
        $plainRemarks = 'Internal contractor notes for security review';

        $project = Project::create([
            'project_code' => 'ENC-001',
            'project_name' => 'Encrypted Remarks Test',
            'project_type' => 'Roads',
            'remarks' => $plainRemarks,
            'current_status' => 'Planning',
        ]);

        $this->assertNotSame($plainRemarks, $project->getRawOriginal('remarks'));
        $this->assertSame($plainRemarks, $project->remarks);

        $project->refresh();

        $this->assertSame($plainRemarks, $project->remarks);
    }
}
