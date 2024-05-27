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

def extract_color_values(image, num_colors=8):
    image_rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)
    pixels = image_rgb.reshape((-1, 3))
    kmeans = KMeans(n_clusters=num_colors)
    kmeans.fit(pixels)
    return kmeans.cluster_centers_

def find_matching_color(color_samples, target_colors):
    distances = pairwise_distances(color_samples, target_colors, metric='euclidean')
    matching_indices = np.argmin(distances, axis=0)
    return matching_indices

if __name__ == "__main__":
    logam_image_path = sys.argv[1]
    standar_image_path = sys.argv[2]

    if not os.path.isfile(logam_image_path):
        print(f"Error: {logam_image_path} does not exist.")
        sys.exit(1)
    
    if not os.path.isfile(standar_image_path):
        print(f"Error: {standar_image_path} does not exist.")
        sys.exit(1)
    
    image_logam = cv2.imread(logam_image_path)
    image_standar = cv2.imread(standar_image_path)

    # Check if images are loaded correctly
    if image_logam is None:
        print(f"Error: Failed to load image at {logam_image_path}")
        sys.exit(1)
    
    if image_standar is None:
        print(f"Error: Failed to load image at {standar_image_path}")
        sys.exit(1)

    color_values_logam = extract_color_values(image_logam)
    color_values_standar = extract_color_values(image_standar)

    matching_indices = find_matching_color(color_values_logam, color_values_standar)

    data = pd.DataFrame({
        'Image 1 Color Index': ['1a', '1b', '2b', '2c'],
        'Image 2 Matching Color Index': [matching_indices[i] + 1 for i in range(4)]
    })

    output_dir = os.path.dirname(logam_image_path)
    
    # Generate timestamp for filenames
    timestamp = datetime.now().strftime('%Y%m%d_%H%M%S')

    csv_path = os.path.join(output_dir, f'matching_colors_{timestamp}.csv')
    data.to_csv(csv_path, index=False)

    plt.figure(figsize=(10, 5))
    matching_color_distances = [color_distance(color_values_logam[i], color_values_standar[matching_indices[i]]) for i in range(4)]
    matching_color_distances = matching_color_distances[:4]
    plt.bar(['1a', '1b', '2b', '2c'], matching_color_distances, color='blue')
    plt.title('Matching Color Distances')
    plt.xlabel('Color Index in Image 1')
    plt.ylabel('Distance to Matching Color in Image 2')

    png_path = os.path.join(output_dir, f'matching_color_distances_{timestamp}.png')
    pdf_path = os.path.join(output_dir, f'matching_color_distances_{timestamp}.pdf')
    
    plt.savefig(png_path, bbox_inches='tight')
    plt.savefig(pdf_path, bbox_inches='tight')

    print(f"{csv_path},{png_path},{pdf_path}")
