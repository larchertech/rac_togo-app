import React from 'react'
import { useDropzone } from 'react-dropzone'
import { Upload, File } from 'lucide-react'
import { cn } from './Button'

export function FileUpload({ onDrop, accept, maxSize = 5242880, label = 'Glissez-déposez vos fichiers ici' }) {
  const { getRootProps, getInputProps, isDragActive, acceptedFiles } = useDropzone({
    onDrop,
    accept: accept ? { [accept]: [] } : undefined,
    maxSize,
  })

  return (
    <div
      {...getRootProps()}
      className={cn(
        'border-2 border-dashed rounded-lg p-6 text-center cursor-pointer transition-colors',
        isDragActive ? 'border-rac-gold bg-rac-gold/5' : 'border-gray-300 hover:border-gray-400'
      )}
    >
      <input {...getInputProps()} />
      <Upload size={24} className="text-gray-400 mx-auto mb-2" />
      <p className="text-sm text-gray-600">{label}</p>
      <p className="text-xs text-gray-400 mt-1">Max {maxSize / 1048576} Mo</p>
      {acceptedFiles.length > 0 && (
        <div className="mt-3 space-y-1">
          {acceptedFiles.map((file, i) => (
            <div key={i} className="flex items-center gap-2 text-xs text-gray-600 bg-white px-2 py-1 rounded">
              <File size={12} />
              {file.name}
            </div>
          ))}
        </div>
      )}
    </div>
  )
}
