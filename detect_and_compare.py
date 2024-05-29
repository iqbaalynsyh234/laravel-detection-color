import cv2
import numpy as np
import pandas as pd
import matplotlib.pyplot as plt
from sklearn.cluster import KMeans
from sklearn.metrics import pairwise_distances
import sys
import os
from datetime import datetime

def color_distance(color1, color2):
    return np.sqrt(np.sum((color1 - color2) ** 2))

# Function to extract dominant color values from an image
def extract_color_values(image, num_colors=8):
    image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    pixels = image_rgb.reshape((-1, 3))
    kmeans = KMeans(n_clusters=num_colors)
    kmeans.fit(pixels)
    return kmeans.cluster_centers_

# Function to find all matching color indices
def find_all_color_indices(color_samples, target_colors):
    distances = pairwise_distances(color_samples, target_colors, metric='euclidean')
    matching_indices = np.argmin(distances, axis=0)
    return matching_indices

# Function to generate color labels
def generate_color_labels(num_colors):
    labels = []
    suffixes = ['a', 'b', 'c', 'd', 'e']
    for i in range(num_colors):
        for suffix in suffixes:
            labels.append(f'{i + 1}{suffix}')
    return labels

# Main function
if __name__ == "__main__":
    logam_image_path = sys.argv[1]
    standar_image_path = sys.argv[2]

    # Check for the existence of image files
    if not os.path.isfile(logam_image_path) or not os.path.isfile(standar_image_path):
        print(f"Error: File not found.")
        sys.exit(1)
    
    # Read images
    image_logam = cv2.imread(logam_image_path)
    image_standar = cv2.imread(standar_image_path)

    # Check for successful image loading
    if image_logam is None or image_standar is None:
        print(f"Error: Failed to load image.")
        sys.exit(1)

    # Perform color extraction and matching
    num_colors_logam = 8  
    color_values_logam = extract_color_values(image_logam, num_colors=num_colors_logam)
    color_values_standar = extract_color_values(image_standar)
    all_matching_indices = find_all_color_indices(color_values_logam, color_values_standar)

    color_labels_logam = generate_color_labels(num_colors_logam)

    # Create DataFrame for results
    all_data = pd.DataFrame({
        'Image 1 Color Index': color_labels_logam[:len(all_matching_indices)],
        'Image 2 Matching Color Index': [all_matching_indices[i] + 1 for i in range(len(all_matching_indices))]
    })

    # Set up output directory and timestamp
    output_dir = os.path.dirname(logam_image_path)
    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')

    # Save results to CSV
    csv_path = os.path.join(output_dir, f'matching_colors_{timestamp}.csv')
    all_data.to_csv(csv_path, index=False)

    plt.figure(figsize=(10, 5))
    matching_color_distances = [color_distance(color_values_logam[i], color_values_standar[all_matching_indices[i]]) for i in range(len(all_matching_indices))]
    plt.bar(all_data['Image 1 Color Index'], matching_color_distances, color='blue')
    plt.title('Matching Color Distances')
    plt.xlabel('Color Index in Image 1')
    plt.ylabel('Distance to Matching Color in Image 2')

    # Save visualization to PNG PDF and excel
    png_path = os.path.join(output_dir, f'matching_color_distances_{timestamp}.png')
    pdf_path = os.path.join(output_dir, f'matching_color_distances_{timestamp}.pdf')
    plt.savefig(png_path, bbox_inches='tight')
    plt.savefig(pdf_path, bbox_inches='tight')

    print(f"{csv_path},{png_path},{pdf_path}")
